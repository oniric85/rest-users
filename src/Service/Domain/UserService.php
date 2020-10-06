<?php

namespace Oniric85\UsersService\Service\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Oniric85\UsersService\Exception\Application\NotFromSwitzerlandException;
use Oniric85\UsersService\Message\UserMessage;
use Oniric85\UsersService\Service\Infrastructure\IpApiClient;
use Oniric85\UsersService\Entity\User;
use Oniric85\UsersService\Exception\Application\EmailAlreadyUsedException;
use Oniric85\UsersService\Repository\UserRepository;
use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface;

class UserService
{
    private UserRepository $repository;
    private EntityManagerInterface $em;
    private IpApiClient $ipApiClient;
    private MessageBusInterface $bus;

    private const SWITZERLAND_COUNTRY_CODE = 'CH';

    public function __construct(
        UserRepository $repository,
        EntityManagerInterface $em,
        IpApiClient $ipApiClient,
        MessageBusInterface $bus
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->ipApiClient = $ipApiClient;
        $this->bus = $bus;
    }

    public function createUser(string $email, string $plainTextPassword, string $firstName, string $ip): User
    {
        if ($this->repository->findOneByEmail($email)) {
            throw new EmailAlreadyUsedException();
        }

        if ($this->ipApiClient->getCountryCodeFromIp($ip) !== self::SWITZERLAND_COUNTRY_CODE) {
            throw new NotFromSwitzerlandException();
        }

        $hashedPassword = password_hash($plainTextPassword, PASSWORD_DEFAULT);

        if ($hashedPassword === false) {
            throw new RuntimeException('Error hashing password.');
        }

        $user = new User($email, $hashedPassword, $firstName);

        $this->em->persist($user);
        $this->em->flush();

        $this->bus->dispatch(new UserMessage($user->getId()->toString()));

        return $user;
    }

    public function updateUser(User $user, ?string $newEmail, ?string $newPlainTextPassword, ?string $newFirstName): void
    {
        if ($newEmail && $newEmail !== $user->getEmail()) {
            if ($this->repository->findOneByEmail($newEmail)) {
                throw new EmailAlreadyUsedException();
            }

            $user->setEmail($newEmail);
        }

        if ($newPlainTextPassword) {
            $newHashedPassword = password_hash($newPlainTextPassword, PASSWORD_DEFAULT);

            if ($newHashedPassword === false) {
                throw new RuntimeException('Error hashing password.');
            }

            $user->setPassword($newHashedPassword);
        }

        if ($newFirstName) {
            $user->setFirstName($newFirstName);
        }

        $this->em->flush();

        $this->bus->dispatch(new UserMessage($user->getId()->toString()));
    }
}