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

Route::get('/users',[UserController::class,'Users']);

Route::get('/user/{user}',[UserController::class,'find']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any','.*');
