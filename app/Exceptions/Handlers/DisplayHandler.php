<?php

namespace App\Exceptions\Handlers;

use Illuminate\Support\Collection;

final class DisplayHandler extends \Exception
{
    private Collection $errors;

    public function setErrors(Collection $errors): DisplayHandler
    {
        $this->errors = $errors;
        return $this;
    }

    public function getErrors(): Collection
    {
        return $this->errors;
    }
}
