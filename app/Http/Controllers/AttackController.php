<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Attack;

use App\Http\Requests;

class AttackController extends Controller
{
    public function list($room)
    {
        return Attack::list($room);
    }
}
