<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    return view('welcome');
});
// Route::get('/index', function () {
//     return view('index');
// })->name('index');

Route::get('/login', function () {
    return response()->json([
        "status" => 401,
        "message" => "You are not logged in, please login first"
    ], 401);
})->name('login');
