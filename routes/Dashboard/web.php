<?php

use App\Service\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'status' => 'Connected'
    ], 200);
})->name('home');

Route::get('/pusher/test/client', function () {
    if(config('app.env') == 'local' || config('app.env') == 'test')
        return view('test.pusher_test_client');
    else
        return response()->json([
            'message' => 'Not Authorized',
            'status' => 'Connected'
        ], 401);

})->name('pusher');
