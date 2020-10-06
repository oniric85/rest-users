<?php

namespace Oniric85\UsersService\Http\Request\Model;

use Symfony\Component\Validator\Constraints as Assert;

class SearchUsers
{
    /**
     * @Assert\Type(
     *     type="string",
     *     message="The email must be of type {{ type }}.",
     *     groups="Strict"
     * )
     */
    private $email;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="The first_name must be of type {{ type }}.",
     *     groups="Strict"
     * )
     */
    private $first_name;

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
