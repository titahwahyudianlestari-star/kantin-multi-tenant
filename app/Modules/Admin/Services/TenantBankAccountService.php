<?php

namespace App\Modules\Admin\Services;

use App\Models\Tenant;
use App\Models\TenantBankAccount;
use Illuminate\Support\Facades\DB;

class TenantBankAccountService
{
    public function __construct(
        private AuditLogger $audit
    ) {}

    public function getByTenant(Tenant $tenant)
    {
        return TenantBankAccount::query()
            ->where('tenant_id', $tenant->id)
            ->orderByDesc('is_primary')
            ->orderBy('bank_name')
            ->get();
    }

    public function create(
        Tenant $tenant,
        array $data
    ): TenantBankAccount {
        return DB::transaction(function () use ($tenant, $data): TenantBankAccount {
            $isPrimary = ($data['is_primary'] ?? false) === true;
            $status = $data['status'] ?? 'active';

            if ($isPrimary && $status !== 'active') {
                $isPrimary = false;
            }

            if ($isPrimary) {
                TenantBankAccount::query()
                    ->where('tenant_id', $tenant->id)
                    ->update([
                        'is_primary' => false,
                    ]);
            }

            $account = TenantBankAccount::create([
                'tenant_id' => $tenant->id,
                'verified_by' => null,
                'bank_name' => $data['bank_name'],
                'account_number_cipher' => $data['account_number_cipher'],
                'account_number_last4' => $data['account_number_last4'],
                'account_name' => $data['account_name'],
                'status' => $status,
                'is_primary' => $isPrimary,
            ]);

            $this->audit->record(
                'bank_account_created',
                $tenant->id,
                null,
                [
                    'bank_name' => $account->bank_name,
                    'account_number_last4' => $account->account_number_last4,
                    'account_name' => $account->account_name,
                    'status' => $account->status,
                    'is_primary' => $account->is_primary,
                ]
            );

            return $account;
        });
    }

    public function update(
        TenantBankAccount $bankAccount,
        array $data
    ): TenantBankAccount {
        return DB::transaction(function () use ($bankAccount, $data): TenantBankAccount {
            $before = [
                'bank_name' => $bankAccount->bank_name,
                'account_number_last4' => $bankAccount->account_number_last4,
                'account_name' => $bankAccount->account_name,
                'status' => $bankAccount->status,
                'is_primary' => $bankAccount->is_primary,
            ];

            $status = $data['status'];
            $isPrimary = ($data['is_primary'] ?? false) === true;

            if ($status !== 'active') {
                $isPrimary = false;
            }

            if ($isPrimary) {
                TenantBankAccount::query()
                    ->where('tenant_id', $bankAccount->tenant_id)
                    ->where('id', '!=', $bankAccount->id)
                    ->update([
                        'is_primary' => false,
                    ]);
            }

            $updateData = [
                'bank_name' => $data['bank_name'],
                'account_name' => $data['account_name'],
                'status' => $status,
                'is_primary' => $isPrimary,
            ];

            if (
                isset($data['account_number_cipher']) &&
                isset($data['account_number_last4'])
            ) {
                $updateData['account_number_cipher'] =
                    $data['account_number_cipher'];

                $updateData['account_number_last4'] =
                    $data['account_number_last4'];
            }

            if ($status !== 'active') {
                $updateData['verified_by'] = null;
            }

            $bankAccount->update($updateData);

            $bankAccount = $bankAccount->fresh();

            $after = [
                'bank_name' => $bankAccount->bank_name,
                'account_number_last4' => $bankAccount->account_number_last4,
                'account_name' => $bankAccount->account_name,
                'status' => $bankAccount->status,
                'is_primary' => $bankAccount->is_primary,
            ];

            $this->audit->record(
                'bank_account_updated',
                $bankAccount->tenant_id,
                $before,
                $after
            );

            return $bankAccount;
        });
    }

    public function verify(
        TenantBankAccount $bankAccount,
        int $userId
    ): TenantBankAccount {
        return DB::transaction(function () use ($bankAccount, $userId): TenantBankAccount {
            $before = [
                'status' => $bankAccount->status,
                'verified_by' => $bankAccount->verified_by,
                'is_primary' => $bankAccount->is_primary,
            ];

            $bankAccount->update([
                'status' => 'active',
                'verified_by' => $userId,
            ]);

            $bankAccount = $bankAccount->fresh();

            $after = [
                'status' => $bankAccount->status,
                'verified_by' => $bankAccount->verified_by,
                'is_primary' => $bankAccount->is_primary,
            ];

            $this->audit->record(
                'bank_account_verified',
                $bankAccount->tenant_id,
                $before,
                $after
            );

            return $bankAccount;
        });
    }

    public function reject(
        TenantBankAccount $bankAccount
    ): TenantBankAccount {
        return DB::transaction(function () use ($bankAccount): TenantBankAccount {
            $before = [
                'status' => $bankAccount->status,
                'verified_by' => $bankAccount->verified_by,
                'is_primary' => $bankAccount->is_primary,
            ];

            $bankAccount->update([
                'status' => 'rejected',
                'verified_by' => null,
                'is_primary' => false,
            ]);

            $bankAccount = $bankAccount->fresh();

            $after = [
                'status' => $bankAccount->status,
                'verified_by' => $bankAccount->verified_by,
                'is_primary' => $bankAccount->is_primary,
            ];

            $this->audit->record(
                'bank_account_rejected',
                $bankAccount->tenant_id,
                $before,
                $after
            );

            return $bankAccount;
        });
    }
}
