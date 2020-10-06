<?php

namespace Oniric85\UsersService\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private string $hashedPassword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    public function __construct(string $email, string $hashedPassword, string $firstName)
    {
        $this->id = Uuid::uuid4();
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
        $this->firstName = $firstName;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $newEmail): self
    {
        $this->email = $newEmail;

        return $this;
    }

    public function setFirstName(string $newFirstName): self
    {
        $this->firstName = $newFirstName;

        return $this;
    }

    public function setPassword(string $newHashedPassword): self
    {
        $this->hashedPassword = $newHashedPassword;

        return $this;
    }
}
