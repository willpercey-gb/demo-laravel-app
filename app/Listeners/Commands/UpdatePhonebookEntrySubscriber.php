<?php

namespace App\Listeners\Commands;

use App\Events\Commands\CreatePhoneBookEntry;
use App\Events\Commands\UpdatePhonebookEntry;
use App\Repositories\Phonebook\EntryRepository;
use App\Repositories\PhonebookRepository;
use Illuminate\Events\Dispatcher;

class UpdatePhonebookEntrySubscriber
{
    public function __construct(
        private EntryRepository $repository,
        private PhonebookRepository $phonebookRepository
    ) {
    }

    public function __invoke(UpdatePhonebookEntry $message): void
    {
        $entry = $this->repository->findOneBy(['uuid' => $message->getEntryUuid()]);

        if (!$entry) {
            //TODO exception here
        }

        $entry->fill(
            $message->except('entryUuid')->toArray()
        );
        $entry->phonebook()->associate($this->phonebookRepository->findOneBy([]));

        $entry->saveOrFail();
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(UpdatePhonebookEntry::class, self::class);
    }
}
