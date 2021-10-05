<?php

namespace Tests\Unit;

use App\Http\Controllers\V1\Phonebook\EntryController;
use App\Models\Phonebook\Entry;
use App\Repositories\Phonebook\EntryRepository;
use Database\Seeders\SystemSeeder;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SystemSeeder::class);
    }

    public function testRepositoryCanFetchResult(): void
    {
        /** @var Entry $createdEntry */
        $createdEntry = Entry::factory()->create();

        /** @var EntryRepository $repository */
        $repository = app()->make(EntryRepository::class);

        /** @var Entry $locatedEntry */
        $locatedEntry = $repository->findOneBy(['uuid' => $createdEntry->uuid]);

        $this->assertSame($createdEntry->uuid, $locatedEntry->uuid);
        $this->assertSame($createdEntry->first_name, $createdEntry->first_name);
        $this->assertSame($createdEntry->last_name, $createdEntry->last_name);
    }
}
