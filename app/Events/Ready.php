<?php

namespace App\Events;

use App\Battle;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * This event represents when a user clicks the
 * ready button after sitting down in a seat.
 **/
class Ready extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $room;
    public $seat;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room, $seat)
    {
        $this->room = $room;
        $this->seat = $seat;

        Battle::SetReady($room, $seat);
        $ready = Battle::GetReady($room);

        if ($ready['ready1'] && $ready['ready2']) {
            Battle::StartBattle($room);
        }
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
