<?php

namespace Erenkucukersoftware\BrugsMigrationTool\Events;


use Illuminate\Broadcasting\{PrivateChannel, PresenceChannel, InteractsWithSockets, Channel};
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProductsCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timestamp;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
