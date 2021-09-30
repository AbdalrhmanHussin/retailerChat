<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;


class UserController extends Controller
{
    /**
     * Get all users
     */
    public function Users() 
    {
        $users = User::limit(10)->get();
        return result::repsonse(true,$users);
    }
   
    /**
     * Return a specific user
     */
    public function find(User $user)
    {
        return result::repsonse(true,$user);
    }

    public function login(Request $request)
    {
        $user = User::login($request->only(['email','password']),$request->remember);
        return $user;
    }

    public function register(Request $request)
    {
        $user = User::validate(['name','email','password'],$request->all())
                    ->register();
        return $user;
    }

    public function forget(Request $request)
    {
        $data = [];
        $data['email'] = $request->only('email');
        User::checkEmail($data['email'])->mail('forget',$data);
        return result::repsonse(true);
    }

    public function checkToken(Request $request)
    {
        $token = User::checkToken($request->token,$request->email);
        return $token;
    }

    public function changepassword(Request $request)
    {
        $changepassword = User::checkToken($request->token,$request->email);
        if($changepassword['message'])
        {
            $user_check = User::validate(['password'],$request->only('password','password_confirmation'))
                            ->changepassword($request->only(['email','password']));
            return $user_check;

        }
    }

    public function socialite($drive) 
    {
        return Socialite::driver($drive)->redirect();
    }

    public function resocialite($drive)
    {
        $user = Socialite::driver($drive)->user();
        User::socialite($user);
        return \redirect()->route('vue',['any'=>'chat']);
    }

    public function notfriends(Request $request)
    {
        $auth = Auth::id();
        if(isset($auth))
        {
            $notfriends = User::notfriends($auth,$start=0,$end=5);
        } else {
            dd('failed no atuh');
        }
        return $notfriends;
    }

    public function request(Request $request)
    {
        User::request($request->only('id'));
    }

    public function pending()
    {
        $pending = User::pending();
        return result::repsonse(true,$pending);
    }
}
