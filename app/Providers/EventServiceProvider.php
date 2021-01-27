<?php

namespace App\Providers;

use App\Events\FileExportedEvent;
use App\Events\NewLikeHasBeenAddedEvent;
use App\Listeners\CsvFileExportedListener;
use App\Listeners\NotifyOwnerListener;
use App\Listeners\PdfFileExportedListener;
use App\Listeners\UpdateLikesCountListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        FileExportedEvent::class => [
            CsvFileExportedListener::class,
            PdfFileExportedListener::class,
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
