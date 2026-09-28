<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTenantRole;
use App\Modules\Admin\Services\AssignTenantRole;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTenantRoleController
{
    public function index(Tenant $tenant): View
    {
        $users = User::orderBy('name')->get();

        $roles = UserTenantRole::with('user')
            ->where('tenant_id', $tenant->id)
            ->get();

        return view('admin::tenants.roles', [
            'tenant' => $tenant,
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function assign(
        Request $request,
        Tenant $tenant,
        AssignTenantRole $service
    ): RedirectResponse {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'in:owner,operator,cashier'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        $service->assign(
            $tenant,
            $user,
            $validated['role']
        );

        return redirect()
            ->route('admin.tenants.roles.index', $tenant)
            ->with('success', 'Role tenant berhasil ditambahkan.');
    }

    public function remove(
        Request $request,
        Tenant $tenant,
        AssignTenantRole $service
    ): RedirectResponse {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'in:owner,operator,cashier'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        try {
            $service->remove(
                $tenant,
                $user,
                $validated['role']
            );
        } catch (DomainException $e) {
            return redirect()
                ->route('admin.tenants.roles.index', $tenant)
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.tenants.roles.index', $tenant)
            ->with('success', 'Role tenant berhasil dihapus.');
    }
}
