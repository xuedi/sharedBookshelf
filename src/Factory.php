<?php declare(strict_types=1);

namespace SharedBookshelf;

use Awurth\SlimValidation\Validator as FormValidator;
use Doctrine\DBAL\Types\Type as DbalType;
use Doctrine\ORM\Configuration as DoctrineConfiguration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Persistence\Mapping\Driver\StaticPHPDriver;
use Gregwar\Captcha\CaptchaBuilder;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Doctrine\UuidType;
use SharedBookshelf\Controller\Errors\Error404Controller;
use SharedBookshelf\Controller\Errors\ErrorsController;
use SharedBookshelf\Controller\FormValidators\SignupFormValidator;
use SharedBookshelf\Controller\HomeController;
use SharedBookshelf\Controller\ImagesController;
use SharedBookshelf\Controller\LoginController;
use SharedBookshelf\Controller\PrivacyController;
use SharedBookshelf\Controller\SignupController;
use SharedBookshelf\Controller\TermsController;
use SharedBookshelf\Entities\User;
use SharedBookshelf\Repositories\UserRepository;
use SimpleLog\Logger as SimpleLogger;
use Slim\App as Slim;
use Slim\Factory\AppFactory;
use Symfony\Component\Console\Helper\HelperSet;
use Twig\Environment as Twig;
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

    public function setup(): void
    {
        $this->framework = $this->createFramework();
        $this->framework->registerController($this->createHomeController());
        $this->framework->registerController($this->createLoginController());
        $this->framework->registerController($this->createSignupController());
        $this->framework->registerController($this->createImagesController());
        $this->framework->registerController($this->createTermsController());
        $this->framework->registerController($this->createPrivacyController());
        $this->framework->registerErrorController($this->createError404Controller());
    }

    public function run(): void
    {
        $this->framework = $this->createFramework();
        $this->framework->run();
    }

    public function getDoctrineCliHelperSet(): HelperSet
    {
        return ConsoleRunner::createHelperSet(
            $this->createEntityManager()
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
            $this->createConfiguration()
        );
    }

    private function createSignupController(): SignupController
    {
        return new SignupController(
            $this->createTwig(),
            $this->createConfiguration(),
            $this->createCaptchaBuilder(),
            $this->createSignupFormValidator(),
            $this->createEntityManager()->getRepository(User::class)
        );
    }

    private function createSignupFormValidator(): SignupFormValidator
    {
        return new SignupFormValidator(
            $this->createFormValidator()
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
        $cache = $config->getCachePath();
        if ($config->getDebugLevel()) {
            $cache = false;
        }

        return new Twig($this->createTwigTemplates(), [
            'cache' => $cache
        ]);
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
}
