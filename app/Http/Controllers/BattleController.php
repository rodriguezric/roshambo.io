<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Battle;

use App\Events\Ready;

use App\Http\Requests;

class BattleController extends Controller
{
    /**
     * Gets the ready status for both seats in a room.
     * @param string $room The room.
     * @return array Array of Redis results for the 
     *  ready parameters.
     **/
    public function ready($room)
    {
        return Battle::GetReady($room);         
    }


    /**
     * Checks if there is a battle currently in 
     * progress in the room.
     * @param string $room The room.
     * @return int 1 or null.
     **/
    public function battle($room)
    {
        return Battle::IsBattleInRoom($room);
    }

    /**
     * Sets the ready parameter for a seat in a room.
     * @param Request $request The post request. Has
     *   room and seat parameters.
     * @return Request The post request sent.
     **/
    public function SetReady(Request $request)
    {
        event(new Ready($request->room, $request->seat));
        return $request;
    }
}
