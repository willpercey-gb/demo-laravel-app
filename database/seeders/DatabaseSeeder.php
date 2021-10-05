<?php

namespace Database\Seeders;

use App\Repositories\PhonebookRepository;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SystemSeeder::class);
        $this->call(DemoSeeder::class);
    }
}
