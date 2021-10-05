<?php

namespace App\Exceptions;

use App\Exceptions\Contracts\ExceptionContext;
use App\Exceptions\Contracts\LoggedException;
use App\Exceptions\Contracts\MultiException;

class ValidationException extends \Exception implements MultiException, ExceptionContext, LoggedException
{
    private $context;
    private \Traversable $errors;

    public function setContext($context): static
    {
        $this->context = $context;
        return $this;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setErrors(\Traversable $errors): static
    {
        $this->errors = $errors;
        return $this;
    }

    public function getErrors(): \Traversable
    {
        return $this->errors;
    }

    public function getLogLevel(): string
    {
        return self::LOG_DEBUG;
    }
}
