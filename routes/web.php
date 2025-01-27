<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::controller(ServicesController::class)->group(function(){
    Route::get('auth/github', 'redirectToGithub');
    Route::get('auth/github/callback', 'handleGithubCallback');

    Route::get('auth/google', 'redirectToGoogle');
    Route::get('auth/google/callback', 'handleGoogleCallback');
});