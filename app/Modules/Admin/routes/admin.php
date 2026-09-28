<?php

use App\Modules\Admin\Http\Controllers\AdminCommissionController;
use App\Modules\Admin\Http\Controllers\AdminTenantBankAccountController;
use App\Modules\Admin\Http\Controllers\AdminTenantController;
use App\Modules\Admin\Http\Controllers\AdminTenantRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/tenants', [
    AdminTenantController::class,
    'index',
])->name('tenants.index');

Route::get('/tenants/create', [
    AdminTenantController::class,
    'create',
])->name('tenants.create');

Route::post('/tenants', [
    AdminTenantController::class,
    'store',
])->name('tenants.store');

Route::get('/tenants/{tenant}/edit', [
    AdminTenantController::class,
    'edit',
])->name('tenants.edit');

Route::put('/tenants/{tenant}', [
    AdminTenantController::class,
    'update',
])->name('tenants.update');

/*
|--------------------------------------------------------------------------
| Tenant Roles
|--------------------------------------------------------------------------
*/

Route::get('/tenants/{tenant}/roles', [
    AdminTenantRoleController::class,
    'index',
])->name('tenants.roles.index');

Route::post('/tenants/{tenant}/roles', [
    AdminTenantRoleController::class,
    'assign',
])->name('tenants.roles.assign');

Route::delete('/tenants/{tenant}/roles', [
    AdminTenantRoleController::class,
    'remove',
])->name('tenants.roles.remove');

/*
|--------------------------------------------------------------------------
| Tenant Commissions
|--------------------------------------------------------------------------
*/

Route::get('/tenants/{tenant}/commissions', [
    AdminCommissionController::class,
    'index',
])->name('tenants.commissions.index');

Route::get('/tenants/{tenant}/commissions/create', [
    AdminCommissionController::class,
    'create',
])->name('tenants.commissions.create');

Route::post('/tenants/{tenant}/commissions', [
    AdminCommissionController::class,
    'store',
])->name('tenants.commissions.store');

Route::get('/tenants/{tenant}/commissions/{commission}/edit', [
    AdminCommissionController::class,
    'edit',
])->name('tenants.commissions.edit');

Route::put('/tenants/{tenant}/commissions/{commission}', [
    AdminCommissionController::class,
    'update',
])->name('tenants.commissions.update');

/*
|--------------------------------------------------------------------------
| Tenant Bank Accounts
|--------------------------------------------------------------------------
*/

Route::get('/tenants/{tenant}/bank-accounts', [
    AdminTenantBankAccountController::class,
    'index',
])->name('tenants.bank-accounts.index');

Route::get('/tenants/{tenant}/bank-accounts/create', [
    AdminTenantBankAccountController::class,
    'create',
])->name('tenants.bank-accounts.create');

Route::post('/tenants/{tenant}/bank-accounts', [
    AdminTenantBankAccountController::class,
    'store',
])->name('tenants.bank-accounts.store');

Route::get('/tenants/{tenant}/bank-accounts/{bankAccount}/edit', [
    AdminTenantBankAccountController::class,
    'edit',
])->name('tenants.bank-accounts.edit');

Route::put('/tenants/{tenant}/bank-accounts/{bankAccount}', [
    AdminTenantBankAccountController::class,
    'update',
])->name('tenants.bank-accounts.update');

Route::post('/tenants/{tenant}/bank-accounts/{bankAccount}/verify', [
    AdminTenantBankAccountController::class,
    'verify',
])->name('tenants.bank-accounts.verify');

Route::post('/tenants/{tenant}/bank-accounts/{bankAccount}/reject', [
    AdminTenantBankAccountController::class,
    'reject',
])->name('tenants.bank-accounts.reject');
