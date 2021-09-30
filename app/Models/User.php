<?php

namespace App\Models;

use App\Exceptions\rule;
use App\Mail\forget as MailForget;
use App\result;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

    static $checkEmail = false;

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
            'email' => 'required|email|unique:users',
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

    static function login($user,$remember = false) 
    {
       if(Auth::attempt($user,$remember))
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
            return self::$validate;
    }

    static function mail($type,$data=[])
    {
        if($type == 'forget')
        {
            if(self::$checkEmail) {
                $email = $data['email']['email'];
                $token = \uniqid('RF_');
                $data['token'] = $token;
                self::insertResetToken($token,$email);
                Mail::to($data['email'])
                    ->send(new MailForget($token.'/'.$email));
            } 
        }
    }

    static function insertResetToken($token,$email)
    {
        DB::table('password_resets')->where(['email' => $email])->delete();
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => Now()
        ]);
    }

    static function checkToken($token,$email)
    {
        $call = DB::table('password_resets')
                    ->where([
                       'email' => $email 
                    ])->select(['token','created_at'])->get()->first();
        if(!empty($call)) {
            if(password_verify($token,$call->token)) {
                $now = \strtotime(time());
                if(round((strtotime('now') - strtotime(date($call->created_at),2)) / 3600) < 4)
                {
                    return result::repsonse(true,'Verified Token');
                } else {
                    return result::repsonse(false,'Expired Token');
                }
            } else {
                return result::repsonse(false,'Expired Token');
            }
        } else {
            return result::repsonse(false,'Bad Token');
        }
    }

    static function checkEmail($datatocheck)
    {
        $check = User::where('email',$datatocheck)->get()->first();
        if(!empty($check))
        {
           self::$checkEmail = true;
        } else {
            self::$checkEmail = false;
        }
        return new self;
    }

    static function changepassword($data)
    {
        if(self::$validate['message'])
        {
            User::where('email',$data['email'])
            ->update([
                'password' => Hash::make($data['password'])
            ]);
            DB::table('password_resets')->where('email',$data['email'])->delete();
            return result::repsonse(true);
        } else 
            return result::repsonse(false,self::$validate['payload']);
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

    static function notfriends($user=1,$start=0,$end=5)
    {
        $friends = DB::select("SELECT * FROM users where id <> $user AND id NOT IN (SELECT user_id from friends where friend_id = $user ) AND id NOT IN (SELECT friend_id from friend_request where user_id = $user ) limit $start,$end");
        
        return $friends;
    }

    static function pending()
    {
        if(Auth::check())
        {
            $users = User::with('pendingUsers')->whereId(Auth::id())->get();
            return $users;

        }
    }

    static function request($id)
    {
        if(Auth::check())
        {
            $currentUser = Auth::id();
            DB::table('friend_request')->insert([
                'user_id' => $currentUser,
                'friend_id' => $id['id'],
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);
        } 
        else 
        {
            throw 'Faild to Auth';
        }
    }

    //relations

    public function pendingUsers()
    {
        return $this->belongsToMany(User::class,'friend_request','user_id','friend_id');
    }

}
