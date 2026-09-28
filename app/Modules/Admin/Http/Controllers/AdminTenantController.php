<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\Canteen;
use App\Models\Tenant;
use App\Modules\Admin\Http\Requests\StoreTenantRequest;
use App\Modules\Admin\Services\CreateTenant;
use App\Modules\Admin\Services\UpdateTenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminTenantController
{
    public function index(): View
    {
        $tenants = Tenant::with('canteen')
            ->orderBy('code')
            ->get();

        return view('admin::tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        $canteens = Canteen::orderBy('code')->get();

        return view('admin::tenants.create', compact('canteens'));
    }

    public function store(
        StoreTenantRequest $request,
        CreateTenant $createTenant
    ): RedirectResponse {
        $createTenant->handle($request->validated());

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant berhasil dibuat.');
    }

    public function edit(Tenant $tenant): View
    {
        $canteens = Canteen::orderBy('code')->get();

        return view('admin::tenants.edit', compact('tenant', 'canteens'));
    }

    public function update(
        StoreTenantRequest $request,
        Tenant $tenant,
        UpdateTenant $updateTenant
    ): RedirectResponse {
        $updateTenant->handle($tenant, $request->validated());

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant berhasil diperbarui.');
    }
}
