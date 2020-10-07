<?php

namespace Oniric85\UsersService\Service\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Oniric85\UsersService\Entity\User;
use Oniric85\UsersService\Exception\Application\EmailAlreadyUsedException;
use Oniric85\UsersService\Exception\Application\NotFromSwitzerlandException;
use Oniric85\UsersService\Message\UserMessage;
use Oniric85\UsersService\Repository\UserRepository;
use Oniric85\UsersService\Service\Infrastructure\IpApiClientInterface;
use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface;

class UserService
{
    private UserRepository $repository;
    private EntityManagerInterface $em;
    private IpApiClientInterface $ipApiClient;
    private MessageBusInterface $bus;

    private const SWITZERLAND_COUNTRY_CODE = 'CH';

    public function __construct(
        UserRepository $repository,
        EntityManagerInterface $em,
        IpApiClientInterface $ipApiClient,
        MessageBusInterface $bus
    ) {
        $this->repository = $repository;
        $this->em = $em;
        $this->ipApiClient = $ipApiClient;
        $this->bus = $bus;
    }

    public function createUser(
        string $email,
        string $plainTextPassword,
        string $firstName,
        string $address,
        string $ip
    ): User {
        if ($this->repository->findOneByEmail($email)) {
            throw new EmailAlreadyUsedException();
        }

        if (self::SWITZERLAND_COUNTRY_CODE !== $this->ipApiClient->getCountryCodeFromIp($ip)) {
            throw new NotFromSwitzerlandException();
        }

        $hashedPassword = password_hash($plainTextPassword, PASSWORD_DEFAULT);

        if (false === $hashedPassword) {
            throw new RuntimeException('Error hashing password.');
        }

        $user = new User($email, $hashedPassword, $firstName, $address);

        $this->em->persist($user);
        $this->em->flush();

        $this->bus->dispatch(new UserMessage($user->getId()->toString()));

        return $user;
    }

    public function updateUser(
        User $user,
        ?string $newEmail,
        ?string $newPlainTextPassword,
        ?string $newFirstName,
        ?string $newAddress
    ): void {
        if ($newEmail && $newEmail !== $user->getEmail()) {
            if ($this->repository->findOneByEmail($newEmail)) {
                throw new EmailAlreadyUsedException();
            }

            $user->setEmail($newEmail);
        }

        if ($newPlainTextPassword) {
            $newHashedPassword = password_hash($newPlainTextPassword, PASSWORD_DEFAULT);

            if (false === $newHashedPassword) {
                throw new RuntimeException('Error hashing password.');
            }

            $user->setPassword($newHashedPassword);
        }

        if ($newFirstName) {
            $user->setFirstName($newFirstName);
        }

        if ($newAddress) {
            $user->setAddress($newAddress);
        }

        $this->em->flush();

        $this->bus->dispatch(new UserMessage($user->getId()->toString()));
    }
}
