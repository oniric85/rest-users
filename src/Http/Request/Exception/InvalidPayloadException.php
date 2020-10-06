<?php

namespace Oniric85\UsersService\Http\Request\Exception;

use RuntimeException;

class InvalidPayloadException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid request body.');
    }
}