<?php

namespace App\Listeners;

use App\Events\GetPlayList;
use App\Events\GetPlayListEvent;
use App\Models\PlayListStatistics;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterPlayListStatistics
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GetPlayListEvent $event): void
    {
        $playListStatistics = new PlayListStatistics($event->playListStatistics);
        $playListStatistics->save();
    }
}
