<?php

use App\Http\Middleware\CheckAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1')->group(function () {
    Route::prefix('/accounts')->group(function () {
        Route::post('/signup', "AccountsController@signup");
        Route::post('/signin', "AccountsController@signin");
    });

    Route::prefix('/memories')->group(function () {
        Route::post('/create', "MemoriesController@create")->middleware(CheckAccount::class);
        Route::post('/update', "MemoriesController@update")->middleware(CheckAccount::class);
        Route::post('/get', "MemoriesController@get")->middleware(CheckAccount::class);
        Route::post('/delete', "MemoriesController@delete")->middleware(CheckAccount::class);
    });
});
