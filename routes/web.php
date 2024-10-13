<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AccountController;

//MOVIES
Route::get('movies', [MovieController::class, 'index']);
Route::post('movies', [MovieController::class, 'store']);
Route::get('movies/{id}', [MovieController::class, 'show']);
Route::put('movies/{id}', [MovieController::class, 'update']);
Route::delete('movies/{id}', [MovieController::class, 'destroy']);

//ACCOUNTS
Route::post('register', [AccountController::class, 'register']);
Route::post('login', [AccountController::class, 'login']);
Route::get('accounts/{id}', [AccountController::class, 'getAccount']);
Route::put('accounts/{id}', [AccountController::class, 'updateAccount']);
Route::delete('accounts/{id}', [AccountController::class, 'deleteAccount']);