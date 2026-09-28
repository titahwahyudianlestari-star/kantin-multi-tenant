<?php

namespace Database\Seeders;

use App\Models\Canteen;
use App\Models\CommissionScheme;
use App\Models\DiningTable;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuModifierGroup;
use App\Models\ModifierGroup;
use App\Models\ModifierOption;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserCanteenRole;
use App\Models\UserTenantRole;
use App\Modules\Tenant\TenantContext;
use Illuminate\Database\Seeder;

class DemoCanteenSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@kantin.test'],
            [
                'name' => 'Admin Demo',
                'password' => bcrypt('password'),
            ]
        );

        $canteen = Canteen::firstOrCreate(
            ['code' => 'KNT01'],
            [
                'slug' => 'kantin-demo',
                'tax_rate' => 0,
                'service_fee_rate' => 0,
                'status' => 'active',
            ]
        );

        $tenant1 = Tenant::firstOrCreate(
            [
                'canteen_id' => $canteen->id,
                'code' => 'TENANT01',
            ],
            [
                'slug' => 'tenant-01',
                'status' => 'active',
            ]
        );

        $tenant2 = Tenant::firstOrCreate(
            [
                'canteen_id' => $canteen->id,
                'code' => 'TENANT02',
            ],
            [
                'slug' => 'tenant-02',
                'status' => 'active',
            ]
        );

        UserCanteenRole::firstOrCreate([
            'user_id' => $user->id,
            'canteen_id' => $canteen->id,
            'role' => 'admin',
        ]);

        UserTenantRole::firstOrCreate([
            'user_id' => $user->id,
            'tenant_id' => $tenant1->id,
            'role' => 'owner',
        ]);

        DiningTable::firstOrCreate(
            [
                'canteen_id' => $canteen->id,
                'code' => 'MEJA01',
            ],
            [
                'label' => 'Meja 1',
                'zone' => 'Indoor',
                'status' => 'active',
            ]
        );

        $tenantContext = app(TenantContext::class);
        $tenantContext->set($tenant1);

        $category = MenuCategory::firstOrCreate(
            [
                'tenant_id' => $tenant1->id,
                'slug' => 'makanan',
            ],
            [
                'name' => 'Makanan',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $menu = Menu::firstOrCreate(
            [
                'tenant_id' => $tenant1->id,
                'slug' => 'nasi-goreng',
            ],
            [
                'category_id' => $category->id,
                'name' => 'Nasi Goreng',
                'price_amount' => 15000,
                'stock_qty' => 20,
                'is_available' => true,
                'prep_minutes' => 10,
            ]
        );

        $modifierGroup = ModifierGroup::firstOrCreate(
            [
                'tenant_id' => $tenant1->id,
            ],
            [
                'min_select' => 0,
                'max_select' => 1,
                'is_active' => true,
            ]
        );

        $modifierOption = ModifierOption::firstOrCreate(
            [
                'tenant_id' => $tenant1->id,
                'group_id' => $modifierGroup->id,
                'name' => 'Telur',
            ],
            [
                'price_delta' => 3000,
                'stock_qty' => 10,
                'is_available' => true,
            ]
        );

        MenuModifierGroup::firstOrCreate(
            [
                'menu_id' => $menu->id,
                'modifier_group_id' => $modifierGroup->id,
            ],
            [
                'tenant_id' => $tenant1->id,
                'sort_order' => 1,
            ]
        );

        CommissionScheme::firstOrCreate(
            [
                'tenant_id' => $tenant1->id,
                'valid_from' => now()->toDateString(),
            ],
            [
                'commission_rate' => 10.00,
                'valid_to' => null,
            ]
        );

        $tenantContext->clear();
    }
}
