<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        $user = User::login($request->only(['email','password']));
        return $user;
    }

    public function register(Request $request)
    {
        $user = User::validate(['name','email','password','password_confirmation'],$request->all())
                    ->register();
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
}
