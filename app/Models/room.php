<?php

namespace App\Models;

use App\Events\message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Messages;

class room extends Model
{
    use HasFactory;

    static $from;

    static $to;

    static $room;


    public function users()
    {
        return $this->belongsToMany(User::class,'user_room','room_id','user_id');
    }

    public function messages()
    {
        return $this->hasMany(messages::class)->latest();
    }

    public function latestMessage()
    {
        return $this->hasOne(messages::class)->latest();
    }

    public  function getMessages()
    {
        return $this->hasMany(messages::class)->latest('id')->offset(self::$from)->take(10);
    }

    public static function room($room)
    {
        self::$room = $room;

        return new self;
    }

    public static function to($to)
    {
        self::$to = $to;

        return new self;
    }

    public static function from($from)
    {
        self::$from = $from;

        return new self;
    }


}
