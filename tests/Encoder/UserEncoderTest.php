<?php

namespace Oniric85\UsersService\Tests\Encoder;

use Oniric85\UsersService\Encoder\UserEncoder;
use Oniric85\UsersService\Entity\User;
use PHPUnit\Framework\TestCase;

class UserEncoderTest extends TestCase
{
    public function testEncodeIsSuccessful()
    {
        $encoder = new UserEncoder();

        $user = new User('test@example.com', 'foo', 'Rossi');

        $encodedUser = $encoder->encode($user);

        $this->assertCount(3, $encodedUser);
        $this->assertArrayHasKey('email', $encodedUser);
        $this->assertArrayHasKey('first_name', $encodedUser);
        $this->assertArrayHasKey('id', $encodedUser);
        $this->assertSame('test@example.com', $encodedUser['email']);
        $this->assertSame('Rossi', $encodedUser['first_name']);
    }
}
