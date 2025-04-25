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
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}