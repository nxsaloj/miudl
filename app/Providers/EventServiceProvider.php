<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\EventInserted' => [
            'App\Listeners\EventInsertedListener',
        ],
        'App\Events\EventUpdated' => [
            'App\Listeners\EventUpdatedListener',
        ],
        'App\Events\EventDeleted' => [
            'App\Listeners\EventDeletedListener',
        ],
        'App\Events\EventDestroyed' => [
            'App\Listeners\EventDestroyedListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
