<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    /**
     * List all seats for room name $room.
     * @param string $room Room name
     **/
    public static function ListSeats($room)
    {
        $seat1 = Redis::get($room.':'.'1');
        $seat2 = Redis::get($room.':'.'2');
        $seats = compact('seat1', 'seat2');

        return $seats;
    }

    /**
     * User $user sits down in room $room on
     * seat $seat.
     * @param string $room Room name.
     * @param string $seat Number of the seat. (1 or 2)
     * @param string $user Name of the user.
     **/
    public static function Sitdown($room, $seat, $user)
    {
        Redis::set($room.':'.$seat, $user);
    }

    /**
     * Frees the seat $seat in room $room.
     * @param string $room Room name.
     * @param string $seat Number of the seat. (1 or 2)
     **/
    public static function Standup($room, $seat)
    {
        Redis::del($room.':'.$seat);
    }
}
