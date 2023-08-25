<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', [\App\Http\Controllers\Auth\UserAuthController::class, 'login'])->name('api.auth.login');
});

Route::group(['middleware' => ['api.auth']], function () {
    Route::group(['prefix' => 'repositories'], function() {
        Route::get('', [\App\Http\Controllers\Repository\RepositoriesController::class, 'index'])->name('api.repositories.index');
        Route::get('{repository}', [\App\Http\Controllers\Repository\RepositoriesController::class, 'show'])->name('api.repositories.show');
        Route::get('{repository}/leaderboard', [\App\Http\Controllers\User\LeaderBoardsController::class, 'index'])->name('api.repositories.leaderboard.index');

    });


});
