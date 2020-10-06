<?php

namespace Oniric85\UsersService\Exception\Application;

class EmailAlreadyUsedException extends ApplicationException
{
    public function __construct()
    {
        parent::__construct('The email is already used.');
    }
}