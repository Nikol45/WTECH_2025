<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\MergeSessionCartIntoDatabase;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event / listener mappings for your application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            MergeSessionCartIntoDatabase::class,
        ],
        // … any other events/listeners
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        //
    }

    /**
     * If you’d like Laravel to auto-discover events in your `Listeners` folder,
     * you can turn this on. Otherwise leave it returning `false`.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}