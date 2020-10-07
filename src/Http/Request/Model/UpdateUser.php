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
     * @Assert\Email(
     *     message="The email must be valid."
     * )
     * @Assert\Length(
     *     max=50,
     *     maxMessage="The email maximum length is {{ limit }}."
     * )
     */
    private $email;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="The first_name must be of type {{ type }}.",
     *     groups="Strict"
     * )
     * @Assert\Length(
     *     max=255,
     *     maxMessage="The first_name maximum length is {{ limit }}."
     * )
     */
    private $first_name;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="The password must be of type {{ type }}.",
     *     groups="Strict"
     * )
     * @Assert\Length(
     *     min=8,
     *     minMessage="The password minimum length should be {{ limit }}."
     * )
     */
    private $password;

    /**
     * @Assert\Type(
     *     type="string",
     *     message="The address must be of type {{ type }}.",
     *     groups="Strict"
     * )
     * @Assert\Length(
     *     max=255,
     *     maxMessage="The address maximum length is {{ limit }}."
     * )
     */
    private $address;

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
}
