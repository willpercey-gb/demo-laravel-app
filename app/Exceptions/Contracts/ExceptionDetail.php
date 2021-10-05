<?php


namespace App\Exceptions\Contracts;


interface ExceptionDetail
{
    public function getDetail(): string;
}
