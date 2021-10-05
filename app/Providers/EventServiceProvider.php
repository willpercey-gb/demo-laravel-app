<?php

namespace App\Providers;

use App\Events\RequestValidationEvent;
use App\Listeners\Commands\CreatePhonebookEntrySubscriber;
use App\Listeners\Commands\UpdatePhonebookEntrySubscriber;
use App\Listeners\RequestValidationSubscriber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $subscribe = [
        RequestValidationSubscriber::class,
        CreatePhonebookEntrySubscriber::class,
        UpdatePhonebookEntrySubscriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
