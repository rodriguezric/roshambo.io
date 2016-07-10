<?php

use Illuminate\Support\Facades\Redis;
use App\Events\UserSignedUp;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    //event(new UserSignedUp(Request::query('name')));
    return view('welcome');
});



Route::get('/room', 'RoomController@index');
Route::post('/api/room', 'RoomController@create');




Route::auth();

Route::get('/home', 'HomeController@index');
