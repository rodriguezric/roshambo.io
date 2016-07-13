<?php

namespace App;

use App\Attack;
use App\Health;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    public static function StartBattle($room)
    {
        Redis::set($room.':battle', 1);
        Health::set($room, 1, 3);
        Health::set($room, 2, 3);
    }

    public static function EndBattle($room)
    {
        Redis::del($room.':battle');
        self::SetNotReady($room, 1);
        self::SetNotReady($room, 2);
        Health::delete($room, 1);
        Health::delete($room, 2);
        Attack::delete($room, 1);
        Attack::delete($room, 2);
    }

    public static function IsBattleInRoom($room)
    {
        $response =  Redis::get($room.':battle');
        return compact('response');
    }

    public static function SetReady($room, $seat)
    {
        Redis::set($room.':'.$seat.':ready', 1);
    }

    public static function SetNotReady($room, $seat)
    {
        Redis::del($room.':'.$seat.':ready');
    }

    public static function GetReady($room)
    {
        $ready1 = Redis::get($room.':1:ready');
        $ready2 = Redis::get($room.':2:ready');     

        return compact('ready1', 'ready2');
    }

}
