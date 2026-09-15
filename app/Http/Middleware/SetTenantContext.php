<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Modules\Tenant\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantSlug = $request->route('tenant');

        if (! is_string($tenantSlug)) {
            abort(403);
        }

        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if ($tenant === null || $tenant->status !== 'active') {
            abort(403);
        }

        if (! $request->user()->tenants()->whereKey($tenant->id)->exists()) {
            abort(403);
        }

        $context = app(TenantContext::class);
        $context->set($tenant);

        try {
            return $next($request);
        } finally {
            $context->clear();
        }
    }
}
