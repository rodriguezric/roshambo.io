<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Attack extends Model
{
    public static function GetAttack($room, $seat) 
    {
        return Redis::get($room.':'.$seat.':attack');
    }

    public static function SetAttack($room, $seat, $attack)
    {
        Redis::set($room.':'.$seat.':attack', $attack);
    }

    public static function DeleteAttack($room, $seat)
    {
        Redis::del($room.':'.$seat.':attack');
    }

    public static function ListAttacks($room)
    {
        $attack1 = self::GetAttack($room, 1);
        $attack2 = self::GetAttack($room, 2);
        $attacks = compact('attack1', 'attack2');

        return $attacks
    }

    
}
