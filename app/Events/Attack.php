<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Attack extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $room;
    public $seat;
    public $attack;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room, $seat, $attack)
    {
        $this->room = $room;
        $this->seat = $seat;
        $this->attack = $attack;
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
