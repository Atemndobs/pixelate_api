<?php

namespace App\Listeners;

use App\Events\FileExportedEvent;
use App\Models\Trade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CsvFileExportedListener
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
        //die(json_encode($event));
        $trade = Trade::first();
        $trade->market = $event->type;
        $trade->save();
        Trade::create();
    }
}
