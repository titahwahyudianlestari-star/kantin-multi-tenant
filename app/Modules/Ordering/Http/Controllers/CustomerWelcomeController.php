<?php

namespace App\Modules\Ordering\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Modules\Catalog\Services\TenantOpeningHours;
use App\Modules\Ordering\Services\ResolveCustomerSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class CustomerWelcomeController extends Controller
{
    public function __construct(
        private ResolveCustomerSession $sessions,
        private TenantOpeningHours $hours,
    ) {}

    public function __invoke(
        Request $request,
        Canteen $canteen
    ): View {
        abort_unless(
            $canteen->status === 'active',
            404
        );

        $session = $this->sessions->current($request);

        $table = null;

        if (
            $session !== null
            && $session->diningTable !== null
            && $session->diningTable->canteen_id === $canteen->id
        ) {
            $table = $session->diningTable;
        }

        return view(
            'ordering::welcome',
            [
                'canteen' => $canteen,
                'table' => $table,
                'tenants' => $this->hours
                    ->activeTenants($canteen),
                'hours' => $this->hours,
            ]
        );
    }
}
