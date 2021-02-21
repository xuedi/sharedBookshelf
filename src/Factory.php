<?php declare(strict_types=1);

namespace SharedBookshelf;

use Awurth\SlimValidation\Validator as FormValidator;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader as DoctrineFixtureLoader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Types\Type as DbalType;
use Doctrine\ORM\Configuration as DoctrineConfiguration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Persistence\Mapping\Driver\StaticPHPDriver;
use Gregwar\Captcha\CaptchaBuilder;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Doctrine\UuidType;
use RuntimeException;
use SharedBookshelf\Controller\AdminController;
use SharedBookshelf\Controller\Errors\Error404Controller;
use SharedBookshelf\Controller\Errors\ErrorsController;
use SharedBookshelf\Controller\FormValidators\SignupFormValidator;
use SharedBookshelf\Controller\HomeController;
use SharedBookshelf\Controller\ImagesController;
use SharedBookshelf\Controller\LoginController;
use SharedBookshelf\Controller\PrivacyController;
use SharedBookshelf\Controller\SignupController;
use SharedBookshelf\Controller\TermsController;
use SharedBookshelf\Entities\BookEntity;
use SharedBookshelf\Entities\EventEntity;
use SharedBookshelf\Entities\HandoverEntity;
use SharedBookshelf\Entities\UserEntity;
use SharedBookshelf\Events\Handler\BookHandoverEventHandler;
use SharedBookshelf\Events\Handler\BookReceivedEventHandler;
use SharedBookshelf\Events\Handler\BookRequestEventHandler;
use SharedBookshelf\Events\Handler\LoginEventHandler;
use SharedBookshelf\Playback\LoginPlayback;
use SharedBookshelf\Repositories\BookRepository;
use SharedBookshelf\Repositories\EventRepository;
use SharedBookshelf\Repositories\HandoverRepository;
use SharedBookshelf\Repositories\UserRepository;
use SimpleLog\Logger as SimpleLogger;
use Slim\App as Slim;
use Slim\Factory\AppFactory;
use Symfony\Component\Console\Helper\HelperSet;
use Twig\Environment as Twig;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader as TwigTemplates;

/**
 * @codeCoverageIgnore
 */
class Factory
{
    protected ?Twig $twig = null;
    protected ?Configuration $configuration = null;
    protected ?EntityManager $em = null;
    protected ?Framework $framework = null;
    private File $configFile;

    public function __construct(File $configFile)
    {
        $this->configFile = $configFile;
    }

    public function run(): void
    {
        $this->framework = $this->createFramework(); // TODO: Register controller via autoload mapping
        $this->framework->registerController($this->createHomeController());
        $this->framework->registerController($this->createLoginController());
        $this->framework->registerController($this->createSignupController());
        $this->framework->registerController($this->createImagesController());
        $this->framework->registerController($this->createTermsController());
        $this->framework->registerController($this->createPrivacyController());
        $this->framework->registerController($this->createAdminController());
        $this->framework->registerErrorController($this->createError404Controller());
        $this->framework->run();
    }

    public function process(): void
    {
        $this->framework = $this->createFramework(); // TODO: Register Handler via autoload mapping
        $this->framework->registerEventHandler($this->createLoginEventHandler());
        $this->framework->registerEventHandler($this->createHandoverRequestEventHandler());
        $this->framework->registerEventHandler($this->createHandoverStartedEventHandler());
        $this->framework->registerEventHandler($this->createHandoverConfirmedEventHandler());
        $this->framework->process();
    }

    public function getDoctrineCliHelperSet(): HelperSet
    {
        return ConsoleRunner::createHelperSet(
            $this->createEntityManager()
        );
    }

    public function createFixtureExecutor(): FixtureExecutor
    {
        return new FixtureExecutor(
            $this->createOrmExecutor(),
            $this->createFixtureLoader()
        );
    }

    // #################### Controller ####################

    private function createHomeController(): HomeController
    {
        return new HomeController(
            $this->createTwig(),
            $this->createConfiguration()
        );
    }

    private function createLoginController(): LoginController
    {
        return new LoginController(
            $this->createTwig(),
            $this->createConfiguration(),
            $this->createAuth()
        );
    }

    private function createSignupController(): SignupController
    {
        return new SignupController(
            $this->createTwig(),
            $this->createConfiguration(),
            $this->createCaptchaBuilder(),
            $this->createSignupFormValidator(),
            $this->createUserRepository(),
            $this->createAuth(),
            $this->createCrypto()
        );
    }

    private function createSignupFormValidator(): SignupFormValidator
    {
        return new SignupFormValidator(
            $this->createFormValidator(),
            $this->createUserRepository()
        );
    }

    private function createImagesController(): ImagesController
    {
        return new ImagesController(
            $this->createTwig(),
            $this->createConfiguration()
        );
    }

    private function createTermsController(): TermsController
    {
        return new TermsController(
            $this->createTwig(),
            $this->createConfiguration()
        );
    }

    private function createPrivacyController(): PrivacyController
    {
        return new PrivacyController(
            $this->createTwig(),
            $this->createConfiguration()
        );
    }

    private function createAdminController(): AdminController
    {
        return new AdminController(
            $this->createTwig(),
            $this->createConfiguration(),
            $this->createAuth(),
            $this->createUserRepository(),
            $this->createBookRepository(),
            $this->createEventRepository()
        );
    }

    private function createError404Controller(): ErrorsController
    {
        return new Error404Controller(
            $this->createTwig(),
            $this->createConfiguration()
        );
    }

    private function createFramework(): Framework
    {
        if ($this->framework !== null) {
            return $this->framework;
        }

        $this->framework = new Framework(
            $this->createEventStore(),
            $this->createTwig(),
            $this->createConfiguration(),
            $this->createSlim(),
            $this->createLogger()
        );

        return $this->framework;
    }

    private function createConfiguration(): Configuration
    {
        if ($this->configuration !== null) {
            return $this->configuration;
        }

        $this->configuration = new Configuration(
            $this->createFsWrapper(),
            $this->configFile
        );

        return $this->configuration;
    }

    private function createTwig(): Twig
    {
        if ($this->twig !== null) {
            return $this->twig;
        }

        $config = $this->createConfiguration();

        $twig = new Twig($this->createTwigTemplates(), [
            'cache' => $config->isDebug() ? false : $config->getCachePath(),
            'debug' => $config->isDebug()
        ]);

        if ($config->isDebug()) {
            $twig->addExtension(new DebugExtension());
        }

        return $twig;
    }

    private function createEntityManager(): EntityManager
    {
        if ($this->em !== null) {
            return $this->em;
        }

        DbalType::addType('uuid', UuidType::class);

        $this->em = EntityManager::create(
            $this->createDatabaseConnection(),
            $this->createDatabaseConfig()
        );

        return $this->em;
    }

    private function createLogger(): LoggerInterface
    {
        $channel = 'error';
        return new SimpleLogger(
            $this->createConfiguration()->getErrorLog(),
            $channel
        );
    }

    private function createTwigTemplates(): TwigTemplates
    {
        return new TwigTemplates(
            $this->createConfiguration()->getTemplatePath()
        );
    }

    private function createSlim(): Slim
    {
        return AppFactory::create();
    }

    private function createCaptchaBuilder(): CaptchaBuilder
    {
        return new CaptchaBuilder();
    }

    private function createFormValidator(): FormValidator
    {
        return new FormValidator();
    }

    private function createFsWrapper(): FsWrapper
    {
        return new FsWrapper();
    }

    /**
     * @return array<string, mixed>
     */
    private function createDatabaseConnection(): array
    {
        $dbConfig = $this->createConfiguration()->getDatabase();
        return [
            'dbname' => $dbConfig->getDbname(),
            'user' => $dbConfig->getUsername(),
            'password' => $dbConfig->getPassword(),
            'host' => $dbConfig->getHost(),
            'driver' => 'pdo_mysql',
        ];
    }

    private function createDatabaseConfig(): DoctrineConfiguration
    {
        $setup = Setup::createConfiguration(true);
        $setup->setMetadataDriverImpl(
            new StaticPHPDriver(__DIR__ . '/Entities')
        );

        return $setup;
    }

    private function createAuth(): Auth
    {
        return new Auth(
            $this->createUserRepository(),
            $this->createEventStore()
        );
    }

    private function createFixtureLoader(): DoctrineFixtureLoader
    {
        return new DoctrineFixtureLoader();
    }

    private function createCrypto(): Crypto
    {
        return new Crypto();
    }

    private function createOrmExecutor(): ORMExecutor
    {
        return new ORMExecutor(
            $this->createEntityManager(),
            $this->createORMPurger()
        );
    }

    private function createORMPurger(): ORMPurger
    {
        return new ORMPurger();
    }

    private function createLoginPlayback(): LoginPlayback
    {
        return new LoginPlayback(
            $this->createEventStore(),
            $this->createUserRepository()
        );
    }

    private function createEventStore(): EventStore
    {
        return new EventStore(
            $this->createEventRepository()
        );
    }

    private function createLoginEventHandler(): LoginEventHandler
    {
        return new LoginEventHandler(
            $this->createUserRepository()
        );
    }

    private function createHandoverRequestEventHandler(): BookRequestEventHandler
    {
        return new BookRequestEventHandler(
            $this->createHandover(),
        );
    }

    private function createHandoverStartedEventHandler(): BookHandoverEventHandler
    {
        return new BookHandoverEventHandler(
            $this->createHandover(),
        );
    }

    private function createHandoverConfirmedEventHandler(): BookReceivedEventHandler
    {
        return new BookReceivedEventHandler(
            $this->createHandover(),
        );
    }

    private function createUserRepository(): UserRepository
    {
        $repo = $this->createEntityManager()->getRepository(UserEntity::class);
        if (!$repo instanceof UserRepository) {
            throw new RuntimeException('Could not create repository');
        }
        return $repo;
    }

    private function createBookRepository(): BookRepository
    {
        $repo = $this->createEntityManager()->getRepository(BookEntity::class);
        if (!$repo instanceof BookRepository) {
            throw new RuntimeException('Could not create repository');
        }
        return $repo;
    }

    private function createHandoverRepository(): HandoverRepository
    {
        $repo = $this->createEntityManager()->getRepository(HandoverEntity::class);
        if (!$repo instanceof HandoverRepository) {
            throw new RuntimeException('Could not create repository');
        }
        return $repo;
    }

    private function createEventRepository(): EventRepository
    {
        $repo = $this->createEntityManager()->getRepository(EventEntity::class);
        if (!$repo instanceof EventRepository) {
            throw new RuntimeException('Could not create repository');
        }
        return $repo;
    }

    private function createHandover(): Handover
    {
        return new Handover(
            $this->createUserRepository(),
            $this->createBookRepository(),
            $this->createHandoverRepository(),
        );
    }
}
