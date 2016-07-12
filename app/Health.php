<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Health extends Model
{
    public static function GetHealth($room, $seat) 
    {
        return Redis::get($room.':'.$seat.':health');
    }

    public static function SetHealth($room, $seat, $health)
    {
        Redis::set($room.':'.$seat.':health', $health);
    }

    public static function DeleteHealth($room, $seat)
    {
        Redis::del($room.':'.$seat.':health');
    }

    public static function ListHealths($room)
    {
        $health1 = self::GetHealth($room, 1);
        $health2 = self::GetHealth($room, 2);
        $healths = compact('health1', 'health2');

        return $healths
    }
}
