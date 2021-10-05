<?php

namespace App\Repositories;

use App\Models\Phonebook;
use App\Util\Repository;
use Illuminate\Database\Eloquent\Builder;

class PhonebookRepository extends Repository
{
    protected function filter(Builder $query): void
    {
        // TODO: Implement filter() method.
    }

    protected function model(): string
    {
        return Phonebook::class;
    }
}
