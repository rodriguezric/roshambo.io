<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Room;

class RoomController extends Controller
{
    public function index()
    {
        return view('room');
    }

    public function create(Request $request)
    {
        $room = new Room;
        $room->addRoom($request->name);
        return $request->name;
    }
}
