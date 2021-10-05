<?php

namespace App\Repositories\Phonebook;

use App\Models\Phonebook\Entry;
use App\Util\Repository;
use Illuminate\Database\Eloquent\Builder;

class EntryRepository extends Repository
{
    protected function filter(Builder $query): void
    {
        // TODO: Implement filter() method.
    }

    protected function model(): string
    {
        return Entry::class;
    }
}
