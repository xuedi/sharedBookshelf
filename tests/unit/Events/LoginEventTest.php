<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
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
    private UuidInterface $expectedUserId;
    private IpAddress $expectedIpAddress;
    private LoginEvent $subject;

    public function setUp(): void
    {
        $this->expectedType = EventType::fromString('login');
        $this->expectedUserId = Uuid::uuid4();
        $this->expectedIpAddress = IpAddress::generate();

        $this->subject = LoginEvent::fromParameters(
            $this->expectedUserId,
            $this->expectedIpAddress
        );
    }

    public function testCanRetrieveData(): void
    {
        $this->assertEquals($this->expectedType, $this->subject->getType());
        $this->assertEquals($this->generatePayload(), $this->subject->getPayload());
        $this->assertInstanceOf(DateTime::class, $this->subject->getCreated());
        $this->assertInstanceOf(UuidInterface::class, $this->subject->getEventId());

        $this->assertEquals($this->expectedUserId, $this->subject->getUserId());
        $this->assertEquals($this->expectedIpAddress, $this->subject->getIp());
    }

    public function testBuildFromPayload(): void
    {
        $subject = LoginEvent::fromPayload(
            $this->generatePayload(),
            new DateTime(),
            Uuid::uuid4()
        );

        $this->assertEquals($this->expectedType, $subject->getType());
    }

    private function generatePayload(): array
    {
        return [
            'userId' => $this->expectedUserId->toString(),
            'ip' => $this->expectedIpAddress->asString(),
        ];
    }
}
