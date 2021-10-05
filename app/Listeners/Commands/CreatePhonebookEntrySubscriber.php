<?php

namespace App\Listeners\Commands;

use App\Events\Commands\CreatePhoneBookEntry;
use App\Models\Phonebook\Entry;
use App\Repositories\PhonebookRepository;
use Illuminate\Auth\Events\Login;
use Illuminate\Events\Dispatcher;

class CreatePhonebookEntrySubscriber
{
    public function __construct(private PhonebookRepository $phonebookRepository)
    {
    }

    public function __invoke(CreatePhoneBookEntry $message): void
    {
        $entry = (new Entry())->fill($message->toArray());
        $entry->uuid = $message->getUuid();
        $entry->phonebook()->associate($this->phonebookRepository->findOneBy([]));

        $entry->saveOrFail();
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(CreatePhoneBookEntry::class, self::class);
    }
}
