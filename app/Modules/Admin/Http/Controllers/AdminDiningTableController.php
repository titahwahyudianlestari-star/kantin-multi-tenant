<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DiningTable;
use App\Modules\Admin\Http\Controllers\Concerns\ResolvesManagedCanteen;
use App\Modules\Admin\Services\AuditLogger;
use App\Modules\Admin\Services\QrCodeSvg;
use App\Modules\Admin\Services\QrTokenService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class AdminDiningTableController extends Controller
{
    use AuthorizesRequests, ResolvesManagedCanteen;

    public function __construct(
        private QrTokenService $qr,
        private QrCodeSvg $svg,
        private AuditLogger $audit,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', DiningTable::class);

        $canteen = $this->managedCanteen($request);

        $tables = DiningTable::query()
            ->where('canteen_id', $canteen->id)
            ->with('activeToken')
            ->orderBy('code')
            ->get();

        return view('admin::tables.index', compact('canteen', 'tables'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', DiningTable::class);

        $canteen = $this->managedCanteen($request);

        $data = $request->validate([
            'code' => [
                'required',
                'alpha_dash',
                'max:30',
                Rule::unique('dining_tables', 'code')
                    ->where(
                        fn ($q) => $q->where(
                            'canteen_id',
                            $canteen->id
                        )
                    ),
            ],
            'label' => [
                'required',
                'string',
                'max:60',
            ],
            'zone' => [
                'nullable',
                'string',
                'max:60',
            ],
        ]);

        (new DiningTable)->forceFill([
            'canteen_id' => $canteen->id,
            'code' => $data['code'],
            'label' => $data['label'],
            'zone' => $data['zone'] ?? null,
            'status' => 'active',
        ])->save();

        return redirect()
            ->route('admin.tables.index')
            ->with('status', 'Meja dibuat.');
    }

    public function rotate(DiningTable $table): RedirectResponse
    {
        $this->authorize('rotateQr', $table);

        $plain = $this->qr->rotate($table);

        return redirect()
            ->route('admin.tables.qr', $table)
            ->with('qr_plain', $plain);
    }

    public function qr(DiningTable $table): View
    {
        $this->authorize('rotateQr', $table);

        $plain = session('qr_plain');

        $url = is_string($plain)
            ? route('customer.scan', [
                'token' => $plain,
            ])
            : null;

        $svg = $url !== null
            ? $this->svg->render($url)
            : null;

        return view(
            'admin::tables.qr',
            compact(
                'table',
                'plain',
                'url',
                'svg'
            )
        );
    }

    public function status(
        Request $request,
        DiningTable $table
    ): RedirectResponse {
        $this->authorize('update', $table);

        $data = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],
        ]);

        $before = $table->status;

        $table->forceFill([
            'status' => $data['status'],
        ])->save();

        $this->audit->record(
            $data['status'] === 'active'
                ? 'dining_table.activated'
                : 'dining_table.deactivated',
            null,
            [
                'canteen_id' => $table->canteen_id,
                'dining_table_id' => $table->id,
                'status' => $before,
            ],
            [
                'canteen_id' => $table->canteen_id,
                'dining_table_id' => $table->id,
                'status' => $data['status'],
            ]
        );

        return redirect()
            ->route('admin.tables.index')
            ->with(
                'status',
                $data['status'] === 'active'
                    ? "{$table->label} diaktifkan kembali."
                    : "{$table->label} dinonaktifkan; QR tidak dapat dipakai sampai diaktifkan kembali."
            );
    }
}
