<?php

use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/add-user', [UserController::class, 'store'])->name('add-user');
Route::get('/user/{id}', [UserController::class, 'show'])->name('get-user');
Route::get('/search/users', [UserController::class, 'search'])->name('search-user');
Route::post('/user/update', [UserController::class, 'update'])->name('update-user');
Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('delete-user');