<?php

namespace Oniric85\UsersService\Tests\Service\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Oniric85\UsersService\Exception\Application\NotFromSwitzerlandException;
use Oniric85\UsersService\Message\UserMessage;
use Oniric85\UsersService\Repository\UserRepository;
use Oniric85\UsersService\Service\Domain\UserService;
use Oniric85\UsersService\Service\Infrastructure\IpApiClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

class UserServiceTest extends TestCase
{
    /**
     * @var UserRepository|MockObject
     */
    private $repository;

    /**
     * @var MessageBusInterface|MockObject
     */
    private $bus;

    /**
     * @var EntityManagerInterface|MockObject
     */
    private $em;

    /**
     * @var IpApiClientInterface|MockObject
     */
    private $ipApiClient;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);
        $this->bus = $this->createMock(MessageBus::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->ipApiClient = $this->createMock(IpApiClientInterface::class);
    }

    public function testUserCreationIsSuccessful(): void
    {
        $this->ipApiClient
            ->expects($this->once())
            ->method('getCountryCodeFromIp')
            ->willReturn('CH');

        $message = new UserMessage('testId');

        $this->bus
            ->expects($this->once())
            ->method('dispatch')
            ->willReturn(new Envelope($message));


        $service = new UserService($this->repository, $this->em, $this->ipApiClient, $this->bus);

        $user = $service->createUser('foo@example.com', 'password', 'Rossi', '127.0.0.1');

        $this->assertSame('foo@example.com', $user->getEmail());
        $this->assertSame('Rossi', $user->getFirstName());
    }

    public function testUserCreationThrowsIfUserNotFromSwitzerland(): void
    {
        $this->expectException(NotFromSwitzerlandException::class);

        $this->ipApiClient
            ->expects($this->once())
            ->method('getCountryCodeFromIp')
            ->willReturn('IT');

        $service = new UserService($this->repository, $this->em, $this->ipApiClient, $this->bus);

        $service->createUser('foo@example.com', 'password', 'Rossi', '127.0.0.1');
    }
}
