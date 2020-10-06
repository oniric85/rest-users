<?php

namespace Oniric85\UsersService\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Oniric85\UsersService\Entity\User;

class UserRepository
{
    /**
     * @var EntityRepository<User>
     */
    private EntityRepository $repository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->repository = $registry->getRepository(User::class);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findAllBy(array $filter): array
    {
        return $this->repository->findBy($filter);
    }

    public function findOneById(string $id): ?User
    {
        return $this->repository->findOneBy([
            'id' => $id,
        ]);
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->repository->findOneBy([
            'email' => $email,
        ]);
    }
}
