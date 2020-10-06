<?php

namespace Oniric85\UsersService\Service\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Oniric85\UsersService\Exception\Application\NotFromSwitzerlandException;
use Oniric85\UsersService\Service\Infrastructure\IpApiClient;
use Oniric85\UsersService\Entity\User;
use Oniric85\UsersService\Exception\Application\EmailAlreadyUsedException;
use Oniric85\UsersService\Repository\UserRepository;

class UserService
{
    private UserRepository $repository;
    private EntityManagerInterface $em;
    private IpApiClient $ipApiClient;

    private const SWITZERLAND_COUNTRY_CODE = 'CH';

    public function __construct(UserRepository $repository, EntityManagerInterface $em, IpApiClient $ipApiClient)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->ipApiClient = $ipApiClient;
    }

    public function createUser(string $email, string $plainTextPassword, string $firstName, string $ip): User
    {
        if ($this->repository->findByEmail($email)) {
            throw new EmailAlreadyUsedException();
        }

        if ($this->ipApiClient->getCountryCodeFromIp($ip) !== self::SWITZERLAND_COUNTRY_CODE) {
            throw new NotFromSwitzerlandException();
        }

        $hashedPassword = password_hash($plainTextPassword, PASSWORD_DEFAULT);

        $user = new User($email, $hashedPassword, $firstName);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}