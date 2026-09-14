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
use Illuminate\Database\Seeder;

class DemoCanteenSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@kantin.test',
        ]);

        $canteen = Canteen::create([
            'code' => 'KNT01',
            'slug' => 'kantin-demo',
            'tax_rate' => 0,
            'service_fee_rate' => 0,
            'status' => 'active',
        ]);

        $tenant1 = Tenant::create([
            'canteen_id' => $canteen->id,
            'code' => 'TENANT01',
            'slug' => 'tenant-01',
            'status' => 'active',
        ]);

        $tenant2 = Tenant::create([
            'canteen_id' => $canteen->id,
            'code' => 'TENANT02',
            'slug' => 'tenant-02',
            'status' => 'active',
        ]);

        UserCanteenRole::create([
            'user_id' => $user->id,
            'canteen_id' => $canteen->id,
            'role' => 'admin',
        ]);

        UserTenantRole::create([
            'user_id' => $user->id,
            'tenant_id' => $tenant1->id,
            'role' => 'owner',
        ]);

        DiningTable::create([
            'canteen_id' => $canteen->id,
            'code' => 'MEJA01',
            'label' => 'Meja 1',
            'zone' => 'Indoor',
            'status' => 'active',
        ]);

        $category = MenuCategory::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Makanan',
            'slug' => 'makanan',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $menu = Menu::create([
            'tenant_id' => $tenant1->id,
            'category_id' => $category->id,
            'name' => 'Nasi Goreng',
            'slug' => 'nasi-goreng',
            'price_amount' => 15000,
            'stock_qty' => 20,
            'is_available' => true,
            'prep_minutes' => 10,
        ]);

        $modifierGroup = ModifierGroup::create([
            'tenant_id' => $tenant1->id,
            'min_select' => 0,
            'max_select' => 1,
            'is_active' => true,
        ]);

        $modifierOption = ModifierOption::create([
            'tenant_id' => $tenant1->id,
            'group_id' => $modifierGroup->id,
            'name' => 'Telur',
            'price_delta' => 3000,
            'stock_qty' => 10,
            'is_available' => true,
        ]);

        MenuModifierGroup::create([
            'menu_id' => $menu->id,
            'modifier_group_id' => $modifierGroup->id,
            'tenant_id' => $tenant1->id,
            'sort_order' => 1,
        ]);

        CommissionScheme::create([
            'tenant_id' => $tenant1->id,
            'commission_rate' => 10.00,
            'valid_from' => now()->toDateString(),
            'valid_to' => null,
        ]);
    }
}
