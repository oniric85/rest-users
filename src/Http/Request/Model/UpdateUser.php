<?php

namespace Oniric85\UsersService\Http\Request\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUser
{
    /**
     * @Assert\Type(
     *     type="string",
     *     message="The email must be of type {{ type }}.",
     *     groups="Strict"
     * )
     * @Assert\NotBlank(
     *     message="The email cannot be empty."
     * )
     * @Assert\Email(
     *     message="The email must be valid."
     * )
     */
    private $email;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="The first_name must be of type {{ type }}.",
     *     groups="Strict"
     * )
     * @Assert\NotBlank(
     *     message="The first_name cannot be empty."
     * )
     */
    private $first_name;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="The password must be of type {{ type }}.",
     *     groups="Strict"
     * )
     * @Assert\NotBlank(
     *     message="The password cannot be empty."
     * )
     * @Assert\Length(
     *     min=8,
     *     minMessage="The password minimum length should be {{ limit }}."
     * )
     */
    private $password;

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}