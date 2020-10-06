<?php

namespace Oniric85\UsersService\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Oniric85\UsersService\Entity\User;

class UserRepository
{
    private ObjectRepository$repository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->repository = $registry->getRepository(User::class);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function findAllBy(array $filter): array
    {
        return $this->repository->findBy($filter);
    }

    public function findOneById(string $id): ?User
    {
        return $this->repository->findOneById($id);
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->repository->findOneBy([
            'email' => $email,
        ]);
    }
}
