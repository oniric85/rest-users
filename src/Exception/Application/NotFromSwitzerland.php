<?php

namespace Oniric85\UsersService\Exception\Application;

class NotFromSwitzerland extends ApplicationException
{
    public function __construct()
    {
        parent::__construct('Registration request is not from Switzerland.');
    }
}