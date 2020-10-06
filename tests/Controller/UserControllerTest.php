<?php

namespace Oniric85\UsersService\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testUserCreationIsSuccessful(): void
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
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUserCreationDoesNotAllowEmailReuse(): void
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

        $client->request(
            'POST',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUserUpdateIsSuccessful(): void
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
        $this->assertJson($client->getResponse()->getContent());

        $result = json_decode($client->getResponse()->getContent(), true);

        $updatedData = [
            'first_name' => 'test',
        ];

        $client->request(
            'PUT',
            sprintf('/users/%s', $result['id']),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($updatedData)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());

        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('test', $result['first_name']);
    }

    public function testUserSearchByEmail(): void
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

        $client->request(
            'GET',
            '/users?email=test@example.com'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(1, $result);
    }
}
