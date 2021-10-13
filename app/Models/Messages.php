<?php

namespace App\Models;

use App\Events\message;
use App\Events\NewChatMessage;
use App\result;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Messages extends Model
{
    use HasFactory;
    protected $fillable = ['type','content','readed','user_id','room_id'];
    static $access = false;
    static $userid;
    static $sendto;
    static $roomid;
    static $valid;
    static $content;
    static $type;

    public static function hasAccess($userid,$roomid)
    {
        $accessQuery = DB::table('user_room')->where([
            'room_id' => $roomid,
            'user_id' => $userid
        ])->select('id')->get()->first();


        if(!empty($accessQuery))
        {
            self::$access = true;
            self::$userid = $userid;
            self::$roomid = $roomid;
        } else {
            self::$access = false;
        }

        return new self;
    }

    public static function valid($arr)
    {
        if($arr['type'] = 'text')
        {
            $validation = Validator::make($arr,[
                'content' => 'required|min:1'
            ]);
        } else if($arr['type'] = 'image'){
            $validation = Validator::make($arr,[
                'content' => 'image|requred'
            ]);
        } else {
            throw new Exception('Not a valid type');
        }
        self::$content = $arr['content'];
        self::$type    = $arr['type'];
        ($validation->fails()) ? self::$valid = false : self::$valid = true;

        return new self;
    }

    public static function send($sendto)
    {
        if(self::$access && self::$valid)
        {
            $message = [
                'user_id'  => self::$userid,
                'sendtoid' => $sendto,
                'room_id'  => self::$roomid,
                'content'  => self::$content,
                'type'     => self::$type,
            ];
            self::create($message);

            broadcast(new message($message));

            return result::repsonse(true);
        } 
        else 
        {
            throw new Exception("Not accessable user or this message doenst meet the standered");
        }
    }
}
