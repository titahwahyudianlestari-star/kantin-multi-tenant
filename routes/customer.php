<?php

use App\Modules\Ordering\Http\Controllers\CustomerWelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get(
    '/meja',
    CustomerWelcomeController::class
)->name('welcome');
