<?php

namespace App\Http\Controllers;

use App\Events\FriendRequest;
use App\Events\status;
use App\Models\User;
use App\result;
use Exception;
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
            $notfriends = User::notfriends($auth,$request->start,$request->end,$request->search);
        } else {
           dd('not login');
        }
        return $notfriends;
    }

    public function request(Request $request)
    {
        User::request($request->only('id'));
        Auth::user()->sendto=$request->only('id');
        Auth::user()->password = '';
        broadcast(new FriendRequest(Auth::user()));
    }

    public function pending()
    {
        $pending = User::pending();
        return result::repsonse(true,$pending);
    }

    public function getrequest()
    {
        $request = User::getrequest();
        return result::repsonse(true,$request);
    }

    public function removePending(Request $request)
    {
        User::removePending($request->only('friendid'));
    }

    public function submitRequest(Request $request)
    {
        User::handleRequest($request->id,$request->action);
    }

    public function authorized()
    {
        return Auth::check();
    }

    public function getUserData()
    {
        return Auth::user();
    }

    public function modifyData(Request $request)
    {
        if($request->update == 'image')
        {
            $name = $request->name.'.jpg';
            $path = 'images/users';
            $old_image = 'images/users/' . Auth::user()->image;
            $user = User::validate(['avatar'],$request->only('avatar'))
                            ->modify('image',$name)
                            ->image($request->file('avatar'),$name,$path);
        }
        else if( $request->update == 'name')
        {
            $user = User::validate(['name'],['name' =>$request->value['name']])
                ->modify('name',$request->value['name']);
        }
        else if($request->update == 'password')
        {
            $user = User::validate(['password'],['password' => $request->value['password']['password'],'password_confirmation' => $request->value['password']['password_confirmation']])
                        ->similar($request->value['password']['oldpassword'],Auth::user()->password)
                        ->modify('password',$request->value['password']);
        } 
        else if($request->update == 'status')
        {
            $user = User::validate(['status'],['status' => $request->value['status']])->modify('status',$request->value['status']);
            broadcast(new status(['id' => Auth::id(),'status' => $request->value['status']] ));
        }

        return $user;
    }

    public function logout()
    {
        Auth::logout();
    }

    public function init()
    {
        $user = User::getInit();
        return $user;
    }

    public function test(Request $request)
    {
        dd(User::with('test')->get());
    }

    public function getRooms()
    {
        return User::find(Auth::id())->rooms()->get();
    }
}
