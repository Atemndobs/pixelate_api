<?php

namespace App\Providers;

use App\Events\NewLikeHasBeenAddedEvent;
use App\Listeners\NotifyOwnerListener;
use App\Listeners\UpdateLikesCountListener;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenAdded;
use Cog\Laravel\Love\Reaction\Events\ReactionHasBeenRemoved;
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
         //   ReactionHasBeenAdded::class,
          //  ReactionHasBeenRemoved::class

        ],
        NewLikeHasBeenAddedEvent::class =>[
            NotifyOwnerListener::class,
            UpdateLikesCountListener::class
        ],


    ];

    /**
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
