<?php 

namespace App;

class result {
    static function repsonse($test,$payload=[])
    {
       $test = ($test) ? 'Success' : 'Failed';
       return \response()->json([
           'message' => $test,
           'payload' => $payload
       ]);
    }
}