<?php

namespace Database\Seeders;

use App\Models\Phonebook\Entry;
use App\Models\User;
use App\Repositories\PhonebookRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Yaml\Yaml;

class DemoSeeder extends Seeder
{

    public function __construct(private PhonebookRepository $repository)
    {
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = Yaml::parseFile(database_path('lib/entries.yaml'));

        DB::beginTransaction();

        $user = User::query()->firstOrNew(['email' => 'test@example.com']);
        $user->name = 'Test User';
        $user->password = bcrypt('HelloWorld123@');
        $user->saveQuietly();

        foreach ($data as $datum) {
            $entry = Entry::query()->firstOrNew(['uuid' => $datum['uuid']])->fill($datum);
            $entry->phonebook()->associate($this->repository->findOneBy([]));
            $entry->saveQuietly();
        }
        DB::commit();
    }
}
