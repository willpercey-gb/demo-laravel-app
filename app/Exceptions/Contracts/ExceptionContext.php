<?php


namespace App\Exceptions\Contracts;


interface ExceptionContext
{
    public function setContext($context): static;

    public function getContext();
}
