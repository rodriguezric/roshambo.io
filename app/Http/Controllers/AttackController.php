<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Attack;

use App\Events\Attack as AttackEvent;

use App\Http\Requests;

class AttackController extends Controller
{
    public function list($room)
    {
        return Attack::list($room);
    }

    public function attack(Request $request)
    {
        event(new AttackEvent($request->room, $request->seat, $request->attack));
        return $request;
    }
}
