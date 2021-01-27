<?php

namespace App\Listeners;

use App\Events\FileExportedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PdfFileExportedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FileExportedEvent  $event
     * @return void
     */
    public function handle(FileExportedEvent $event)
    {
        //
    }
}
