<?php

namespace Oniric85\UsersService\Controller;

use Oniric85\UsersService\Encoder\UserEncoder;
use Oniric85\UsersService\Http\Request\Model\CreateUser;
use Oniric85\UsersService\Http\Request\Model\SearchUsers;
use Oniric85\UsersService\Repository\UserRepository;
use Oniric85\UsersService\Service\Domain\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route(
     *     "/users",
     *     name="users_search",
     *     methods={"GET"},
     *     defaults={"_model": "Oniric85\UsersService\Http\Request\Model\SearchUsers"}
 *     )
     */
    public function search(SearchUsers $model, UserRepository $userRepository, UserEncoder $encoder): JsonResponse
    {
        $filter = [];

        if ($model->getEmail()) {
            $filter['email'] = $model->getEmail();
        }

        if ($model->getFirstName()) {
            $filter['firstName'] = $model->getFirstName();
        }

        $users = $userRepository->findAllBy($filter);

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
    public function create(CreateUser $model, UserService $userService, Request $req, UserEncoder $encoder): JsonResponse
    {
        $email = $model->getEmail();
        $plainTextPassword = $model->getPassword();
        $firstName = $model->getFirstName();

        $user = $userService->createUser($email, $plainTextPassword, $firstName, $req->getClientIp());

        return $this->json($encoder->encode($user), Response::HTTP_CREATED);
    }
}
