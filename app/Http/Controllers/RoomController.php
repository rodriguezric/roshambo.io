<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Room;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::ListRooms(); 

        return view('room')->with(compact('rooms'));
    }

    public function create(Request $request)
    {
        Room::AddRoom($request->name);

        return $request->name;
    }
}
