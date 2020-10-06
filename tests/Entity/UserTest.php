<?php

namespace Oniric85\UsersService\Tests\Entity;

use Oniric85\UsersService\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntityCreation(): void
    {
        $user = new User('test@example.com', 'foo', 'Rossi');

        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('Rossi', $user->getFirstName());
    }

    public function testUserEntityUpdate(): void
    {
        $user = new User('test@example.com', 'foo', 'Rossi');

        $user
            ->setEmail('test2@example.com')
            ->setFirstName('Bianchi');

        $this->assertSame('test2@example.com', $user->getEmail());
        $this->assertSame('Bianchi', $user->getFirstName());
    }
}
