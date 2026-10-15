<?php

namespace App\Modules\Ordering\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Services\TenantOpeningHours;
use App\Modules\Ordering\Services\ResolveTableScan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolveTableQrController extends Controller
{
    public function __construct(
        private ResolveTableScan $resolver,
        private TenantOpeningHours $hours,
    ) {}

    public function __invoke(
        Request $request,
        string $token
    ): RedirectResponse|Response {
        $result = $this->resolver->resolve($token);

        /*
         * UC-02 alur 3a:
         * Token tidak valid, expired, revoked,
         * meja nonaktif, atau kantin nonaktif.
         */
        if ($result === null) {
            return response()->view(
                'ordering::scan-invalid',
                [],
                404
            );
        }

        /*
         * UC-02 alur 3b:
         * QR valid, tetapi kantin sedang tutup.
         * CustomerSession tidak dibuat.
         */
        if ($result['status'] === 'closed') {
            $tenants = $this->hours
                ->activeTenants($result['canteen']);

            return response()->view(
                'ordering::canteen-closed',
                [
                    'canteen' => $result['canteen'],
                    'table' => $result['table'],
                    'tenants' => $tenants,
                    'hours' => $this->hours,
                ]
            );
        }

        /*
         * QR valid dan kantin buka:
         * pasang cookie sesi pelanggan anonim.
         */
        $cookie = cookie(
            name: 'customer_session',
            value: $result['plain'],
            minutes: 240,
            path: '/',
            domain: null,
            secure: $request->isSecure()
                || app()->isProduction(),
            httpOnly: true,
            raw: false,
            sameSite: 'lax',
        );

        /*
         * Tahap 8.5:
         * setelah scan berhasil, arahkan pelanggan
         * ke halaman sambutan meja.
         */
        return redirect()
            ->route(
                'customer.welcome',
                [
                    'canteen' => $result['canteen']->slug,
                ]
            )
            ->withCookie($cookie);
    }
}
