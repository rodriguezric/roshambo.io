<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Battle;

use App\Http\Requests;

class BattleController extends Controller
{
    public function ready($room)
    {
        return Battle::GetReady($room);         
    }

    public function battle($room)
    {
        return Battle::IsBattleInRoom($room);
    }
}
