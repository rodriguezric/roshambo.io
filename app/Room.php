<?php

namespace App;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * Add a room to redis with name $name.
     * @param string $name Name of the room.
     **/
    public function addRoom($name)
    {
        Redis::sadd('rooms', $name);
    }
    
    /**
     * Returns an array of rooms stored in redis.
     * @return array Array of room names.
     **/
    public function listRooms()
    {
        return Redis::smembers('rooms');
    }

    /**
     * Removes room from redis with name $name.
     * @param string $name Name of room to remove.
     **/
    public function removeRoom($name)
    {
        Redis::srem('rooms', $name);
    }

    /**
     * Checks if a room already exists.
     * @param string $name Room name.
     * @return int 1 exists, 0 doesn't.
     **/
    public function existsRoom($name)
    {
        return Redis::sismember('rooms', $name);
    }

}
