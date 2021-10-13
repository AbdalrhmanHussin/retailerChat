<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\room;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function send(Request $request)
    {
       $message =  Messages::hasAccess($request->userid,$request->roomid)
                    ->valid(['content'=> $request->content,'type' => $request->type])
                    ->send($request->sendtoid);
        return $message;
    }

    public function render(Request $request)
    {
        $messages = room::from($request->from)->with('getMessages')->whereId($request->room)->get()->toArray();
        return $messages;
    }
}
