<?php

namespace App\Events;

use Aws\Ec2\Ec2Client;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PeakTemperatureEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $peakTemp;

    /**
     * Create a new event instance.
     *
     * @param $peakTemp
     */
    public function __construct(array $peakTemp)
    {
        $this->peakTemp = $peakTemp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('peak-channel');
    }

    public function broadcastWith()
    {
        return [
            'peak' => $this->peakTemp
        ];
    }
}
