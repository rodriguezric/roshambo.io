<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

/**
 * Create a room in Redis with name $name.
 * @param string $name Name of the room.
 **/
class Room extends Model
{
    public function createRoom($name)
    {
        Redis::sadd('rooms', $name);
    }
}
