<?php

namespace App;

use App\Attack;
use App\Health;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    /** 
     * Starts a battle in the room. It sets the battle
     * parameter to 1 and sets the health for both 
     * players to 3.
     * @param string $room The room.
     **/
    public static function StartBattle($room)
    {
        Redis::set($room.':battle', 1);
        Health::set($room, 1, 3);
        Health::set($room, 2, 3);
    }

    /** 
     * Ends a battle and cleans up the Redis database.
     * Delete the battle parameter. Unsets the ready
     * status for both users. Deletes the health
     * parameters. Deletes the attack parameters.
     * @param string $room The room.
     **/
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

    /**
     * Checks if there is a battle currently in 
     * progress in the room.
     * @param string $room The room.
     * @return int 1 or null.
     **/
    public static function IsBattleInRoom($room)
    {
        $response =  Redis::get($room.':battle');
        return compact('response');
    }

    /**
     * Sets the ready parameter for a seat in a room.
     * @param string $room The room.
     * @param int $seat The seat.
     **/
    public static function SetReady($room, $seat)
    {
        Redis::set($room.':'.$seat.':ready', 1);
    }

    /**
     * Delets the ready parameter for a seat in a room.
     * @param string $room The room.
     * @param int $seat The seat.
     **/
    public static function SetNotReady($room, $seat)
    {
        Redis::del($room.':'.$seat.':ready');
    }

    /**
     * Gets the ready status for both seats in a room.
     * @param string $room The room.
     * @return array Array of Redis results for the 
     *  ready parameters.
     **/
    public static function GetReady($room)
    {
        $ready1 = Redis::get($room.':1:ready');
        $ready2 = Redis::get($room.':2:ready');     

        return compact('ready1', 'ready2');
    }

}
