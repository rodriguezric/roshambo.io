<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    private static function GetSeat($room, $seat)
    {
        return Redis::get($room.':'.$seat);
    }
    /**
     * List all seats for room name $room.
     * @param string $room Room name
     **/
    public static function ListSeats($room)
    {
        $seat1 = self::GetSeat($room, 1);
        $seat2 = self::GetSeat($room, 2);
        $seats = compact('seat1', 'seat2');

        return $seats;
    }

    private static function NotSittingInRoom($room, $user)
    {
        $seats = self::ListSeats($room);
        return $seats['seat1'] != $user && $seats['seat2'] != $user;
    }

    /**
     * User $user sits down in room $room on
     * seat $seat.
     * @param string $room Room name.
     * @param string $seat Number of the seat. (1 or 2)
     * @param string $user Name of the user.
     **/
    public static function SitDown($room, $seat, $user)
    {
        if (self::NotSittingInRoom($room,$user)) {
            Redis::set($room.':'.$seat, $user);
            return true;
        }
        return false;
    }

    /**
     * Frees the seat $seat in room $room if the user
     * matches the one in the seat.
     * @param string $room Room name.
     * @param string $seat Number of the seat. (1 or 2)
     * @return bool Result if the standup was successful.
     **/
    public static function StandUp($room, $seat, $user)
    {
        if ($user === self::GetSeat($room, $seat)) {
            Redis::del($room.':'.$seat);
            return true;
        }
        return false;
    }
}
