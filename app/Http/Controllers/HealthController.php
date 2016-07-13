<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Health;

use App\Http\Requests;

class HealthController extends Controller
{
    public function list($room)
    {
        return Health::list($room);
    }
}
