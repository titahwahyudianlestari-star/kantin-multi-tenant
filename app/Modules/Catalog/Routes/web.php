<?php

use App\Modules\Catalog\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'tenant'])
    ->prefix('tenant/{tenant:slug}')
    ->name('tenant.')
    ->group(function (): void {
        Route::get('/menus', [MenuController::class, 'index'])
            ->name('menus.index');

        Route::get('/menus/create', [MenuController::class, 'create'])
            ->name('menus.create');

        Route::post('/menus', [MenuController::class, 'store'])
            ->name('menus.store');

        Route::put('/menus/{menu}', [MenuController::class, 'update'])
            ->name('menus.update');

        Route::patch('/menus/{menu}/deactivate', [MenuController::class, 'deactivate'])
            ->name('menus.deactivate');
    });
