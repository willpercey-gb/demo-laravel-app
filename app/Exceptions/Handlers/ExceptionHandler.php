<?php


namespace App\Exceptions\Handlers;


interface ExceptionHandler
{
    public function getSource(): array;

    public function getTitle(): string;

    public function getDetail();

    public function getMeta(): ?array;
}
