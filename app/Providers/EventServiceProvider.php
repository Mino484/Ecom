<?php

namespace App\Providers;
use App\Events\OrderPlaced;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        /*example
        'App\Events\OrderPlaced' => [
            'App\Listeners\NotifyCustomer',
        */
        Registered::class => [
            SendEmailVerificationNotification::class,

    ],
    ];




    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
