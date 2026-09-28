<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantBankAccount;
use App\Modules\Admin\Services\TenantBankAccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTenantBankAccountController
{
    public function index(
        Tenant $tenant,
        TenantBankAccountService $service
    ): View {
        $bankAccounts = $service->getByTenant($tenant);

        return view('admin::bank_accounts.index', [
            'tenant' => $tenant,
            'bankAccounts' => $bankAccounts,
        ]);
    }

    public function create(Tenant $tenant): View
    {
        return view('admin::bank_accounts.create', [
            'tenant' => $tenant,
        ]);
    }

    public function store(
        Request $request,
        Tenant $tenant,
        TenantBankAccountService $service
    ): RedirectResponse {
        $validated = $request->validate([
            'bank_name' => [
                'required',
                'string',
                'max:255',
            ],
            'account_number' => [
                'required',
                'string',
                'max:100',
            ],
            'account_name' => [
                'required',
                'string',
                'max:255',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
            'is_primary' => [
                'nullable',
                'boolean',
            ],
        ]);

        $accountNumber = $validated['account_number'];

        $validated['account_number_cipher'] = encrypt(
            $accountNumber
        );

        $validated['account_number_last4'] = substr(
            $accountNumber,
            -4
        );

        unset($validated['account_number']);

        $validated['is_primary'] = $request->boolean('is_primary');

        $service->create(
            $tenant,
            $validated
        );

        return redirect()
            ->route(
                'admin.tenants.bank-accounts.index',
                $tenant
            )
            ->with(
                'success',
                'Rekening bank berhasil ditambahkan.'
            );
    }

    public function edit(
        Tenant $tenant,
        TenantBankAccount $bankAccount
    ): View {
        return view('admin::bank_accounts.edit', [
            'tenant' => $tenant,
            'bankAccount' => $bankAccount,
        ]);
    }

    public function update(
        Request $request,
        Tenant $tenant,
        TenantBankAccount $bankAccount,
        TenantBankAccountService $service
    ): RedirectResponse {
        $validated = $request->validate([
            'bank_name' => [
                'required',
                'string',
                'max:255',
            ],
            'account_number' => [
                'nullable',
                'string',
                'max:100',
            ],
            'account_name' => [
                'required',
                'string',
                'max:255',
            ],
            'status' => [
                'required',
                'in:active,inactive',
            ],
            'is_primary' => [
                'nullable',
                'boolean',
            ],
        ]);

        if (! empty($validated['account_number'])) {
            $accountNumber = $validated['account_number'];

            $validated['account_number_cipher'] = encrypt(
                $accountNumber
            );

            $validated['account_number_last4'] = substr(
                $accountNumber,
                -4
            );

            unset($validated['account_number']);
        } else {
            unset($validated['account_number']);
        }

        $validated['is_primary'] = $request->boolean('is_primary');

        $service->update(
            $bankAccount,
            $validated
        );

        return redirect()
            ->route(
                'admin.tenants.bank-accounts.index',
                $tenant
            )
            ->with(
                'success',
                'Rekening bank berhasil diperbarui.'
            );
    }

    public function verify(
        Tenant $tenant,
        TenantBankAccount $bankAccount,
        TenantBankAccountService $service
    ): RedirectResponse {
        $service->verify(
            $bankAccount,
            auth()->id()
        );

        return redirect()
            ->route(
                'admin.tenants.bank-accounts.index',
                $tenant
            )
            ->with(
                'success',
                'Rekening bank berhasil diverifikasi.'
            );
    }

    public function reject(
        Tenant $tenant,
        TenantBankAccount $bankAccount,
        TenantBankAccountService $service
    ): RedirectResponse {
        $service->reject($bankAccount);

        return redirect()
            ->route(
                'admin.tenants.bank-accounts.index',
                $tenant
            )
            ->with(
                'success',
                'Rekening bank berhasil ditolak.'
            );
    }
}
