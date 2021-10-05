<?php


namespace App\Exceptions\Contracts;


interface MultiException
{
    public function setErrors(\Traversable $errors): static;

    public function getErrors(): \Traversable;
}
