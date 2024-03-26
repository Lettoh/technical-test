<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/profiles', [ProfileController::class, 'index']);
Route::post('/profiles', [ProfileController::class, 'store']);
Route::patch('/profiles/{profile}', [ProfileController::class, 'update']);
Route::delete('/profiles/{profile}', [ProfileController::class, 'destroy']);
