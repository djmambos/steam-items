<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test;
use App\Http\Controllers\MainPage;

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

/*Route::get('/', function () {
    return view('main_page');
});*/

Route::get('/', [MainPage::class, 'index']);

Route::controller(Test::class)->group(function () {
    Route::get('/test', 'index');
});

