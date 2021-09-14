<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SomeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $some_useful_data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($some_useful_data)
    {
        $this->some_useful_data = $some_useful_data;
        // we can use this variable in Echo server on front
        //        window.Echo.channel('some-channel')
        //        .listen('SomeEvent', ({some_useful_data}) => {
        //        eventHub.$emit('need-refresh-some-list-event');
        //    });
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('some-channel');
    }
}
