<?php

namespace Oniric85\UsersService\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Oniric85\UsersService\Encoder\UserEncoder;
use Oniric85\UsersService\Entity\User;
use Oniric85\UsersService\Http\Request\Model\CreateUser;
use Oniric85\UsersService\Repository\UserRepository;
use Oniric85\UsersService\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users_search", methods={"GET"})
     */
    public function search(UserRepository $userRepository, UserEncoder $encoder): JsonResponse
    {
        $users = $userRepository->findAll();

        $data = [];

        foreach ($users as $user) {
            $data[] = $encoder->encode($user);
        }

        return $this->json($data);
    }

    /**
     * @Route(
     *     "/users",
     *     name="users_create",
     *     methods={"POST"},
     *     defaults={"_model": "Oniric85\UsersService\Http\Request\Model\CreateUser"}
 *     )
     */
    public function create(CreateUser $model, UserService $userService, Request $req): JsonResponse
    {
        $email = $model->getEmail();
        $firstName = $model->getFirstName();

        $userService->createUser($email, $firstName, $req->getClientIp());

        return $this->json([
            'message' => 'User created successfully!',
        ]);
    }
}
