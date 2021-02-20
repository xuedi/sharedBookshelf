<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;
use SharedBookshelf\IpAddress;

/**
 * @covers \SharedBookshelf\Events\LoginEvent
 * @uses   \SharedBookshelf\EventType
 * @uses   \SharedBookshelf\IpAddress
 */
final class LoginEventTest extends TestCase
{
    private EventType $expectedType;
    private UuidInterface $expectedId;
    private IpAddress $expectedIpAddress;
    private LoginEvent $subject;

    public function setUp(): void
    {
        $this->expectedType = EventType::fromString('login');
        $this->expectedId = Uuid::uuid4();
        $this->expectedIpAddress = IpAddress::generate();

        $this->subject = LoginEvent::fromParameters(
            $this->expectedId,
            $this->expectedIpAddress
        );
    }

    public function testCanGetEventType(): void
    {
        $this->assertEquals($this->expectedType, $this->subject->getType());
    }

    public function testRetrievePayload(): void
    {
        $this->assertEquals($this->generatePayload(), $this->subject->asPayload());
    }

    public function testBuildFromPayload(): void
    {
        $subject = LoginEvent::fromPayload($this->generatePayload());

        $this->assertEquals($this->expectedType, $subject->getType());
    }

    private function generatePayload(): array
    {
        return [
            'userId' => $this->expectedId->toString(),
            'ip' => $this->expectedIpAddress->asString(),
        ];
    }
}
