<?php

namespace Oniric85\UsersService\Tests\Encoder;

use Oniric85\UsersService\Encoder\UserEncoder;
use Oniric85\UsersService\Entity\User;
use PHPUnit\Framework\TestCase;

class UserEncoderTest extends TestCase
{
    public function testEncodeIsSuccessful(): void
    {
        $encoder = new UserEncoder();

        $user = new User(
            'test@example.com',
            'foo',
            'Rossi',
            'Elm street 15, New York'
        );

        $encodedUser = $encoder->encode($user);

        $this->assertCount(4, $encodedUser);
        $this->assertArrayHasKey('email', $encodedUser);
        $this->assertArrayHasKey('first_name', $encodedUser);
        $this->assertArrayHasKey('id', $encodedUser);
        $this->assertArrayHasKey('address', $encodedUser);
        $this->assertSame('test@example.com', $encodedUser['email']);
        $this->assertSame('Rossi', $encodedUser['first_name']);
        $this->assertSame('Elm street 15, New York', $encodedUser['address']);
    }
}
