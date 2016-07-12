<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Health extends Model
{
    public static function get($room, $seat) 
    {
        return Redis::get($room.':'.$seat.':health');
    }

    public static function set($room, $seat, $health)
    {
        Redis::set($room.':'.$seat.':health', $health);
    }

    public static function delete($room, $seat)
    {
        Redis::del($room.':'.$seat.':health');
    }

    public static function list($room)
    {
        $health1 = self::get($room, 1);
        $health2 = self::get($room, 2);
        $healths = compact('health1', 'health2');

        return $healths
    }
}
