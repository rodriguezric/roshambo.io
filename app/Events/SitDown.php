<?php

namespace App\Events;

use App\Seat;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SitDown extends Event implements ShouldBroadcast
{
    public $room;
    public $seat;
    public $user;
    public $result;

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room, $seat, $user)
    {
        $this->room = $room;
        $this->seat = $seat;
        $this->user = $user;

        $this->result = Seat::SitDown($room, $seat, $user);
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['game-channel'];
    }
}
