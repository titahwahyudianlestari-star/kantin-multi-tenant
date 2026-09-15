<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Tenant;
use App\Modules\Catalog\Queries\PublicCatalogQuery;
use App\Modules\Tenant\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function createTestData(): array
    {
        $canteenA = DB::table('canteens')->insertGetId([
            'code' => 'CANTEEN-A',
            'slug' => 'canteen-a',
            'tax_rate' => 0,
            'service_fee_rate' => 0,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $canteenB = DB::table('canteens')->insertGetId([
            'code' => 'CANTEEN-B',
            'slug' => 'canteen-b',
            'tax_rate' => 0,
            'service_fee_rate' => 0,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tenantA = DB::table('tenants')->insertGetId([
            'canteen_id' => $canteenA,
            'code' => 'TENANT-A',
            'slug' => 'tenant-a',
            'status' => 'active',
        ]);

        $tenantB = DB::table('tenants')->insertGetId([
            'canteen_id' => $canteenB,
            'code' => 'TENANT-B',
            'slug' => 'tenant-b',
            'status' => 'active',
        ]);

        $categoryA = DB::table('menu_categories')->insertGetId([
            'tenant_id' => $tenantA,
            'name' => 'Kategori A',
            'slug' => 'kategori-a',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $categoryB = DB::table('menu_categories')->insertGetId([
            'tenant_id' => $tenantB,
            'name' => 'Kategori B',
            'slug' => 'kategori-b',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        DB::table('menus')->insert([
            'tenant_id' => $tenantA,
            'category_id' => $categoryA,
            'name' => 'Menu Tenant A',
            'slug' => 'menu-tenant-a',
            'price_amount' => 10000,
            'stock_qty' => 10,
            'is_available' => true,
            'prep_minutes' => 5,
        ]);

        DB::table('menus')->insert([
            'tenant_id' => $tenantB,
            'category_id' => $categoryB,
            'name' => 'Menu Tenant B',
            'slug' => 'menu-tenant-b',
            'price_amount' => 12000,
            'stock_qty' => 10,
            'is_available' => true,
            'prep_minutes' => 5,
        ]);

        return [
            'tenantA' => $tenantA,
            'tenantB' => $tenantB,
        ];
    }

    public function test_menu_query_only_returns_current_tenant_data(): void
    {
        $data = $this->createTestData();

        $context = app(TenantContext::class);
        $context->set(Tenant::findOrFail($data['tenantB']));

        $menus = Menu::get();

        $this->assertCount(1, $menus);
        $this->assertSame('Menu Tenant B', $menus->first()->name);
        $this->assertSame($data['tenantB'], $menus->first()->tenant_id);
    }

    public function test_query_without_tenant_context_fails_closed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tenant context belum ditentukan.');

        Menu::query()->get();
    }

    public function test_public_catalog_query_can_read_specific_tenant(): void
    {
        $data = $this->createTestData();

        $query = app(PublicCatalogQuery::class);

        $menus = $query->forTenant($data['tenantA']);

        $this->assertCount(1, $menus);
        $this->assertSame('Menu Tenant A', $menus->first()->name);
        $this->assertSame($data['tenantA'], $menus->first()->tenant_id);
    }

    public function test_public_catalog_query_only_returns_available_menu(): void
    {
        $data = $this->createTestData();

        DB::table('menus')
            ->where('tenant_id', $data['tenantA'])
            ->update(['is_available' => false]);

        $query = app(PublicCatalogQuery::class);

        $menus = $query->forTenant($data['tenantA']);

        $this->assertCount(0, $menus);
    }
}
