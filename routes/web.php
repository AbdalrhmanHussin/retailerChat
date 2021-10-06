<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'user'],function(){
    Route::get('/',[UserController::class,'Users']);
    Route::get('/getuser',[UserController::class,'getUserData']);
    Route::get('/authorized',[UserController::class,'authorized']);
    Route::post('/login',[UserController::class,'login']);
    Route::post('/register',[UserController::class,'register']);
    Route::get('/{user}',[UserController::class,'find']);
    Route::post('/forget',[UserController::class,'forget']);
    Route::post('/checktoken',[UserController::class,'checktoken']);
    Route::post('/changepassword',[UserController::class,'changepassword']);
    Route::post('/notfriends',[UserController::class,'notfriends']);
    Route::post('/request',[UserController::class,'request']);
    Route::post('/pending',[UserController::class,'pending']);
    Route::post('/removepending',[UserController::class,'removePending']);
    Route::post('/getrequest',[UserController::class,'getrequest']);
    Route::post('/submitrequest',[UserController::class,'submitRequest']);
    Route::post('/update',[UserController::class,'modifyData']);
    Route::post('/logout',[UserController::class,'logout']);
});

//socialite
Route::get('/socialite/{drive}',[UserController::class,'socialite']);
Route::get('/socialite/{drive}/redirect',[UserController::class,'resocialite']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any','.*')->name('vue');

