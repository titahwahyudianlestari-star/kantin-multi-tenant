<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::prefix('tenant/{tenant:slug}')
        ->scopeBindings()
        ->middleware('role:tenant')
        ->name('tenant.')
        ->group(base_path('routes/tenant.php'));
});

Route::view('/', 'welcome')->name('home');

Route::prefix('kantin/{canteen:slug}')
    ->name('customer.')
    ->group(base_path('routes/customer.php'));

Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('admin.')
    ->group(base_path('routes/admin.php'));

require __DIR__.'/settings.php';
