<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * Create a room in Redis with name $name.
     * @param string $name Name of the room.
     **/
    public function createRoom($name)
    {
        Redis::sadd('rooms', $name);
    }
    
    public function listRooms()
    {
        return Redis::smembers('rooms');
    }
}
