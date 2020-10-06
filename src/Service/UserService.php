<?php

namespace Oniric85\UsersService\Service;

use Doctrine\ORM\EntityManagerInterface;
use Oniric85\UsersService\Entity\User;
use Oniric85\UsersService\Exception\Application\EmailAlreadyUsedException;
use Oniric85\UsersService\Repository\UserRepository;

class UserService
{
    private UserRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(UserRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function createUser(string $email, string $firstName, string $ip): User
    {
        if ($this->repository->findByEmail($email)) {
            throw new EmailAlreadyUsedException();
        }

        $user = new User($email, $firstName);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}