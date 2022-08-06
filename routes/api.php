<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Middleware\XssSanitization;

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

Route::get('/post/{id?}', [PostController::class, 'index']);
Route::delete('/comment/{id}', [PostController::class, 'DeleteComment']);
Route::group(['middleware' => [XssSanitization::class]], function () {
    Route::post('/comment', [PostController::class, 'CreateComment']);
     Route::put('/comment', [PostController::class, 'UpdateComment']);
     });


