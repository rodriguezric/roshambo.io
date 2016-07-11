<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Room;

use App\Events\Sitdown;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $rooms = Room::ListRooms(); 

        return view('room')->with(compact('rooms'));
    }

    public function create(Request $request)
    {
        $name = Room::AddRandomRoom();

        return $name;
    }

    public function GameRoom($room)
    {
        if (Room::ExistsRoom($room)) {
            return view('game.room')->with(compact('room'));
        }
        
        $rooms = Room::ListRooms(); 

        return view('room')->with(compact('rooms'));
    }

    public function Sitdown(Request $request) 
    {
        event(new Sitdown($request->room, $request->seat));
        return $request;
    }
}
