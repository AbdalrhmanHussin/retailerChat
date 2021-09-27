<?php 

namespace App;

class result {
    static function repsonse($test,$payload=[])
    {
        $return = ($test) ? 'success' : 'fail';
        return [
            'message' => $test,
            'payload' => $payload
        ];
    }
}