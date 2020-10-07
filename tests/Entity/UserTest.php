<?php

namespace Oniric85\UsersService\Tests\Entity;

use Oniric85\UsersService\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntityCreation(): void
    {
        $user = new User(
            'test@example.com',
            'foo',
            'Rossi',
            'Elm street 15, New York',
        );

        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('Rossi', $user->getFirstName());
        $this->assertSame('Elm street 15, New York', $user->getAddress());
    }

    public function testUserEntityUpdate(): void
    {
        $user = new User(
            'test@example.com',
            'foo',
            'Rossi',
            'Elm street 15, New York',
        );

        $user
            ->setEmail('test2@example.com')
            ->setFirstName('Bianchi')
            ->setAddress('Elm Street 20, New York');

        $this->assertSame('test2@example.com', $user->getEmail());
        $this->assertSame('Bianchi', $user->getFirstName());
        $this->assertSame('Elm Street 20, New York', $user->getAddress());
    }
}
