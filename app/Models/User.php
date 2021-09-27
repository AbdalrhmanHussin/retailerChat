<?php

namespace App\Models;

use App\Exceptions\rule;
use App\result;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //setter function 
    static $limit = 0;

    static $validate;

    static $values;

    static function limit($limit) 
    {
       self::$limit = $limit;
       return new self;
    }

    //getter functions 
    static function get()
    {
        
        return DB::table('users')
                 ->select()
                 ->limit(self::$limit)
                 ->get();
    }

    static function rules($values = [])
    {
        $rulesArr = [];

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'name'     => 'required|min:3',
        ];

        foreach($values as $key )
        {
           if(isset($rules[$key])) 
           {
                $rulesArr[$key] = $rules[$key];
           } 
        }

        return $rulesArr;
    }

    static function validate($values,$request)
    {
        $rules = self::rules($values);

        self::$values = $request;

        $validate = Validator::make($request,$rules);

        if($validate->fails()) 
            self::$validate = result::repsonse(false,$validate->errors()->messages());
        else
             self::$validate = result::repsonse(true);

        return new self;
    }

    static function login($user) 
    {
       if(Auth::attempt($user))
            {
               return result::repsonse(true);
            } else {
                return result::repsonse(false,['login'=>'Email Address or password is incorrect']);
        }
    }

    static function register()
    {
        if(self::$validate['message'])
        {
            $user = User::create([
                'name' => self::$values['name'],
                'email' => self::$values['email'],
                'password' => Hash::make(self::$values['password'])
            ]);

            Auth::attempt($user->only(['email','password']));

            return result::repsonse(true);
        } else 
            dd(self::$validate);
            return self::$validate;
    }

    static function socialite($user)
    {
        $user = User::firstOrCreate([
            'email' => $user->email
        ],[
            'name' => $user->getName() ?? $user->getNickname(),
            'email' => $user->getEmail(),
            'password' => Hash::make(rand(1,10000))
        ]);

        Auth::attempt($user->only(['email','password']));

        return result::repsonse(true);
    }
}
