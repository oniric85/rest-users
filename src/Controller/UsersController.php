<?php

namespace Oniric85\UsersService\Controller;

use Oniric85\UsersService\Encoder\UserEncoder;
use Oniric85\UsersService\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}
