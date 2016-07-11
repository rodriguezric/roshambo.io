<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Room;
use App\Seat;

use App\Http\Requests;

class SeatController extends Controller
{
    public function retrieve($room)
    {
        return Seat::ListSeats($room);
    }
}
