<?php

namespace App\Events;

use App\Attack as AttackAction;
use App\Health;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Attack extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $room;
    public $seat;
    public $attack;

    private function otherSeat($seat)
    {
        $other = [ "1" => 2, "2" => 1];
        return $other[$seat];
    }

    /**
     * Returns the seat of who should take damage based on
     * an attack transaction. 0 represents no one should 
     * take damage due to a draw.
     * @param string $atatck1 The attack from seat 1.
     * @param string $atatck2 The attack from seat 2.
     * @return int The seat of who should take damage.
     **/
    private function whoTakesDamage($attack1, $attack2)
    {
        $whoTakesDamage = [
                "firefire"   => 0,
                "firewater"  => 1,
                "fireleaf"   => 2,

                "waterfire"  => 2,
                "waterwater" => 0,
                "waterleaf"  => 1,

                "leaffire"   => 1,
                "leafwater"  => 2,
                "leafleaf"   => 0
            ];

        return $whoTakesDamage[$attack1.$attack2];
    }

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

        AttackAction::set($room, $seat, $attack);
        $otherAttack = AttackAction::get($room, $this->otherSeat($seat)); 

        if ($attack && $otherAttack) {
            $whoTakesDamage = $this->whoTakesDamage($attack, $otherAttack);
            if ($seat == 2) {
                $whoTakesDamage = $this->whoTakesDamage($otherAttack, $attack);
            }

            Health::damage($room, $whoTakesDamage);
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
