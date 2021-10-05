<?php

namespace Database\Seeders;

use App\Models\Phonebook;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Symfony\Component\Uid\Uuid;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Phonebook())->fill(
            [
                'uuid' => Uuid::v4(),
                'variation_name' => 'First Publication',
                'publish_date' => CarbonImmutable::now()
            ]
        )->saveQuietly();
    }
}
