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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\room;
use App\Models\Messages;

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
        'status'
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

    //variables 
    static $limit = 0;

    static $validate;

    static $values;

    static $checkEmail = false;

    static $similar;

    static $id;

    //set limit value
    static function limit($limit) 
    {
       self::$limit = $limit;
       return new self;
    }


    //get a define amount of users
    static function get()
    {
        
        return DB::table('users')
                 ->select()
                 ->limit(self::$limit)
                 ->get();
    }

    //add rule for incoming validaition request
    static function rules($values = [])
    {
        $rulesArr = [];

        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'name'     => 'required|min:3',
            'avatar'   => 'required|image',
            'status'   => 'required'
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

    //validate inputs
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


    //Authorized user
    static function login($user,$remember = false) 
    {
       if(Auth::attempt($user,$remember))
            {
               return result::repsonse(true);
            } else {
                return result::repsonse(false,['login'=>'Email Address or password is incorrect']);
        }
    }

    //Register a new user
    static function register()
    {
        if(self::$validate['message'])
        {
            $user = User::create([
                'name' => self::$values['name'],
                'email' => self::$values['email'],
                'password' => Hash::make(self::$values['password'])
            ]);

            $userData = [
                'email' => $user->email,
                'password' => self::$values['password']
            ];
            Auth::attempt($userData,true);
            return result::repsonse(true);
        } else 
            return self::$validate;
    }

    //send mail
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

    //add a new reset token
    static function insertResetToken($token,$email)
    {
        DB::table('password_resets')->where(['email' => $email])->delete();
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => Now()
        ]);
    }

    //check if the provided token is valid
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

    //check that the provided email is registereted email
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

        Auth::loginUsingId($user->id);


        return result::repsonse(true);
    }

    static function notfriends($user=1,$start=0,$end=10,$like='')
    {
        $friends = DB::select("SELECT * FROM users where name LIKE '%$like%' AND id <> $user  and id <> $user AND id NOT IN (SELECT user_id from friends where friend_id = $user ) AND id NOT IN (SELECT friend_id from friend_request where user_id = $user ) limit $start,$end");

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

    static function removePending($friendid)
    {
        $currentUser = Auth::id();
        DB::table('friend_request')->where(['user_id'=>$currentUser,'friend_id'=>$friendid])->delete();
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

    static function getrequest()
    {
        // $requests = DB::select('SELECT users.* FROM user,friend_id where id in (SELECT * FROM )');
        $requests = User::with('friendrequest')->whereId(Auth::id())->get();
        return $requests;
    }

    static function handleRequest($user,$action)
    {
        $request = DB::table('friend_request')
                     ->where(['user_id' => $user,'friend_id'=>Auth::id()]);
        if(!empty($request->get()->toArray()))
        {
            if($action == 'accept')
            {
               $request->delete();
               self::addFriend(Auth::id(),$user);   
            }
            else 
            {
                $request->delete();
            }
        } 
        
    }

    static function addFriend($user1,$user2)
    {
        DB::table('friends')->insert([
            [
                'user_id'   => $user1,
                'friend_id' => $user2
            ],
            [
                'user_id'   => $user2,
                'friend_id' => $user1
            ]
        ]);

        $unique_identifer = uniqid('private'.$user1.$user2);
        DB::table('rooms')->insert([
            [
                'room_unique' => $unique_identifer,
                'user_id' => $user1
            ],
            [
                'room_unique' => $unique_identifer,
                'user_id' => $user2
            ]
        ]);

        $lastRoom = room::orderBy('id','DESC')->select('id')->first()['id'];

        DB::table('user_room')->insert([
            [
                'user_id' => $user1,
                'room_id' => $lastRoom
            ],
            [
                'user_id' => $user2,
                'room_id' => $lastRoom
            ]
        ]);

       
    }

    static function deleteFile($file) 
    {
        if(File::exists(public_path($file)))
        {
            File::delete(public_path($file));
        }

    }

    static function modify($col,$name)
    {  
        if(self::$validate['message']) {
            if($col == 'image')
            {
                self::deleteFile('images/users/'.Auth::user()->image.'.jpg');     
            }

            if($col == 'password' && (!self::$similar) )
            {
                return self::$validate;
            }

            $user = User::find(Auth::id());
            if($col !== 'password') $user->$col = $name; else $user->$col = Hash::make($name['password']);
            $user->save();
            if($col !== 'image') return self::$validate;
        } 
        
        else 
        {
            if($col !== 'image') return self::$validate;
        };
        return new self;
    }

    static function image($file,$name,$path)
    {
        if(self::$validate)
        { 
            $file->move(public_path($path), $name);
        }
    }

    //check if password giving similar to user password
    static function similar($password,$hash)
    {
        if(password_verify($password,$hash))
        {
            self::$similar = true;
            if(empty(self::$validate['payload']['password']))
            {
                self::$validate = result::repsonse(true,[]);
            }
        }
        else
        {
            self::$similar = false;
            self::$validate = result::repsonse(false,['password' => ['The password you enteried doesnt match our recored']]);
        }

        

        return new self;

    }

    public static function messages()
    {
        $messages = DB::select('SELECT * FROM `messages` where room_id IN (SELECT id from rooms where user_id=6 ) order by id desc limit 1');
        return $messages;
    }

    public static function getFriend($id)
    {
        return User::withID($id)->with('friend.rooms.messages')->where(['users.id' => Auth::id()])->get()->toArray()[0]['friend'];
    }

    public static function withID($id)
    {
        static::$id = $id;
        return new self;
    }
    //relations

    public function pendingUsers()
    {
        return $this->belongsToMany(User::class,'friend_request','user_id','friend_id');
    }

    public function friendrequest()
    {
        return $this->belongsToMany(User::class,'friend_request','friend_id','user_id');
    }

    public function message() 
    {
        return $this->hasMany(Messages::class)->take(1);
    }
    
    //get all friends of the Auth user
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id');
    }

    //find if user is friend with auth user
    public  function friend()
    {
        $id = static::$id;
        return  $this->belongsToMany(User::class, 'friends','user_id', 'friend_id')->wherePivot('friend_id',$id);
    }

    public function rooms()
    {
        //get room of auth user
        $rooms =   DB::table('user_room')->where('user_id',Auth::id())->select('room_id')->get()->toArray();
        //flatten the array
        $roomArray = array_column(json_decode(json_encode($rooms), true),'room_id');
        return $this->belongsToMany(room::class,'user_room','user_id','room_id')->whereIn('rooms.id',$roomArray);
    }

    public static function getInit()
    {
        //get users friends and rooms belong to auth user with the last message 
        $user = User::with('friends.rooms.latestMessage')->whereId(Auth::id())->get()->toArray();
        return $user;
    }

   




}
