<?php

namespace Oniric85\UsersService\Http\Request\Exception;

use RuntimeException;

class InvalidParameterException extends RuntimeException
{
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;

        parent::__construct('Invalid parameters.');
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}