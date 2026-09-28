<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\Commission;
use App\Models\Tenant;
use App\Modules\Admin\Services\CommissionService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCommissionController
{
    public function index(
        Tenant $tenant,
        CommissionService $service
    ): View {
        $commissions = $service->getByTenant($tenant);

        return view('admin::commissions.index', [
            'tenant' => $tenant,
            'commissions' => $commissions,
        ]);
    }

    public function create(Tenant $tenant): View
    {
        return view('admin::commissions.create', [
            'tenant' => $tenant,
        ]);
    }

    public function store(
        Request $request,
        Tenant $tenant,
        CommissionService $service
    ): RedirectResponse {
        $validated = $request->validate([
            'commission_rate' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
            'valid_from' => [
                'required',
                'date',
            ],
            'valid_to' => [
                'nullable',
                'date',
                'after_or_equal:valid_from',
            ],
        ]);

        try {
            $service->create($tenant, $validated);
        } catch (DomainException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'valid_from' => $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route('admin.tenants.commissions.index', $tenant)
            ->with('success', 'Commission berhasil ditambahkan.');
    }

    public function edit(
        Tenant $tenant,
        Commission $commission
    ): View {
        return view('admin::commissions.edit', [
            'tenant' => $tenant,
            'commission' => $commission,
        ]);
    }

    public function update(
        Request $request,
        Tenant $tenant,
        Commission $commission,
        CommissionService $service
    ): RedirectResponse {
        $validated = $request->validate([
            'commission_rate' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
            'valid_from' => [
                'required',
                'date',
            ],
            'valid_to' => [
                'nullable',
                'date',
                'after_or_equal:valid_from',
            ],
        ]);

        try {
            $service->update($commission, $validated);
        } catch (DomainException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'valid_from' => $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route('admin.tenants.commissions.index', $tenant)
            ->with('success', 'Commission berhasil diperbarui.');
    }
}
