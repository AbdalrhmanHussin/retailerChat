<?php

use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;
use App\Models\Messages;
use App\Models\room;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/api',[UserController::class,'authorized']);

//Registerations system routes
Route::group(['prefix'=>'auth'],function(){
    Route::post('/login',[UserController::class,'login']);
    Route::post('/register',[UserController::class,'register']);
    Route::post('/forget',[UserController::class,'forget']);
    Route::get('/getuser',[UserController::class,'init']);
    Route::post('/logout',[UserController::class,'logout']);
    //forget password token
    Route::post('/checktoken',[UserController::class,'checktoken']);
    //Authed user 
    Route::get('/authorized',[UserController::class,'authorized']);
    //get specific user
    Route::post('/{user}',[UserController::class,'find']);
    Route::get('/',[UserController::class,'Users']);   
});

Route::group(['middleware' => 'auth','prefix'=> 'message'],function(){
    Route::post('/send',[MessagesController::class,'send']);
    Route::post('/room',[MessagesController::class,'render']);
});

//Friends Zone Routes
Route::group(['middleware' => 'auth','prefix'=> 'user'],function(){
    Route::post('aquariance',[UserController::class,'notfriends']);
    Route::post('send',[MessagesController::class,'send']);
    Route::post('room',[MessagesController::class,'render']);
    Route::post('pending',[UserController::class,'pending']);
    Route::post('getrequest',[UserController::class,'getrequest']);
    Route::post('request',[UserController::class,'request']);
    Route::post('removepending',[UserController::class,'removePending']);
    Route::post('submitrequest',[UserController::class,'submitRequest']);
    Route::post('/update',[UserController::class,'modifyData']);
});



Route::group(['prefix' => 'user'],function(){
    
});


//socialite
Route::get('/socialite/{drive}',[UserController::class,'socialite']);
Route::get('/socialite/{drive}/redirect',[UserController::class,'resocialite']);


Route::get('/test',function(){
    dump(User::getInit());
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any','.*')->name('vue');

