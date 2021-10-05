<?php

namespace Database\Factories\Phonebook;

use App\Models\Phonebook\Entry;
use App\Repositories\PhonebookRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class EntryFactory extends Factory
{
    protected $model = Entry::class;


    #[ArrayShape([
        'uuid' => "string",
        'first_name' => "string",
        'middle_names' => "string",
        'last_name' => "string",
        'email_address' => "string",
        'landline_number' => "string",
        'mobile_number' => "string",
        'phonebook_id' => "mixed"
    ])]
    public function definition(): array
    {
        return [
            'uuid' => (string)Uuid::v4(),
            'first_name' => $this->faker->name(),
            'middle_names' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'email_address' => $this->faker->email(),
            'landline_number' => $this->faker->phoneNumber(),
            'mobile_number' => $this->faker->phoneNumber(),
            'phonebook_id' => app()->make(PhonebookRepository::class)->findOneBy([])?->id
        ];
    }
}
