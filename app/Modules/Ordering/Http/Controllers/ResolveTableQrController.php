<?php

namespace App\Modules\Ordering\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Ordering\Services\ResolveTableScan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ResolveTableQrController extends Controller
{
    public function __construct(
        private ResolveTableScan $resolver
    ) {
    }

    public function __invoke(
        Request $request,
        string $token
    ): RedirectResponse {
        $result = $this->resolver->resolve($token);

        abort_if($result === null, 404);

        $cookie = cookie(
            name: 'customer_session',
            value: $result['plain'],
            minutes: 240,
            path: '/',
            domain: null,
            secure: $request->isSecure() || app()->isProduction(),
            httpOnly: true,
            raw: false,
            sameSite: 'lax',
        );

        return redirect()
            ->route(
                'customer.home',
                ['canteen' => $result['canteen']->slug]
            )
            ->withCookie($cookie);
    }
}