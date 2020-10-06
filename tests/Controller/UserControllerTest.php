<?php

namespace Oniric85\UsersService\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testUserCreation()
    {
        $client = static::createClient();

        $data = [
            'email' => 'test@example.com',
            'first_name' => 'foobar',
            'password' => 'password',
        ];

        $client->request(
            'POST',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}