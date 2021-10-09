<?php

use App\Http\Controllers\UserController;
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
// Route::get('/email',function(){
//    return new App\Mail\forget();
// });
Route::post('/api',[UserController::class,'authorized']);
Route::post('user/forget',[UserController::class,'forget']);
Route::post('user/getrequest',[UserController::class,'getrequest']);
Route::post('user/notfriends',[UserController::class,'notfriends']);
Route::post('user/request',[UserController::class,'request']);
Route::post('user/pending',[UserController::class,'pending']);






Route::group(['prefix' => 'user'],function(){
    Route::get('/',[UserController::class,'Users']);
    Route::get('/getuser',[UserController::class,'init']);
    Route::get('/authorized',[UserController::class,'authorized']);
    Route::post('/login',[UserController::class,'login']);
    Route::post('/register',[UserController::class,'register']);
    Route::post('/{user}',[UserController::class,'find']);
    Route::post('/checktoken',[UserController::class,'checktoken']);
    Route::post('/changepassword',[UserController::class,'changepassword']);
    Route::post('/removepending',[UserController::class,'removePending']);
    Route::post('/submitrequest',[UserController::class,'submitRequest']);
    Route::post('/update',[UserController::class,'modifyData']);
    Route::post('/logout',[UserController::class,'logout']);
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

