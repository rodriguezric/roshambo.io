<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Attack extends Model
{
    public static function get($room, $seat) 
    {
        return Redis::get($room.':'.$seat.':attack');
    }

    public static function set($room, $seat, $attack)
    {
        Redis::set($room.':'.$seat.':attack', $attack);
    }

    public static function deleteAttack($room, $seat)
    {
        Redis::del($room.':'.$seat.':attack');
    }

    public static function list($room)
    {
        $attack1 = self::get($room, 1);
        $attack2 = self::get($room, 2);
        $attacks = compact('attack1', 'attack2');

       return $attacks;
    }

    
}
