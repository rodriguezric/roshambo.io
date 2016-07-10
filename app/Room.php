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
    public static function AddRoom($name)
    {
        Redis::sadd('rooms', $name);
    }
    
    /**
     * Returns an array of rooms stored in redis.
     * @return array Array of room names.
     **/
    public static function ListRooms()
    {
        return Redis::smembers('rooms');
    }

    /**
     * Removes room from redis with name $name.
     * @param string $name Name of room to remove.
     **/
    public static function RemoveRoom($name)
    {
        Redis::srem('rooms', $name);
    }

    /**
     * Checks if a room already exists.
     * @param string $name Room name.
     * @return int 1 exists, 0 doesn't.
     **/
    public static function ExistsRoom($name)
    {
        return Redis::sismember('rooms', $name);
    }

    /**
     * Creates a random string of 10 alphanumberic characters.
     * This is to be used in conjunction with AddRandomRoom.
     * @return string Random 10 character alphanumberic string.
     **/
    private static function RandomString() 
    {
        $alpha_only   = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $alphanumeric = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        return substr(str_shuffle($alpha_only),   0,  1) . 
               substr(str_shuffle($alphanumeric), 0, 10);
    }

    /**
     * Creates a random room name.
     * @return string Room name.
     **/
    private static function RandomRoomName()
    {
        while (self::ExistsRoom($name = self::RandomString()));

        return $name;
    }

    /**
     * Creates a room with a random name.
     * @return string Room name.
     **/
    public static function AddRandomRoom()
    {
        $name = self::RandomRoomName();

        self::AddRoom($name);

        return $name;
    }

}
