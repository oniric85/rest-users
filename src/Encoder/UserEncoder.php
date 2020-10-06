<?php

namespace Oniric85\UsersService\Encoder;

use Oniric85\UsersService\Entity\User;

class UserEncoder
{
    public function encode(User $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
        ];
    }
}