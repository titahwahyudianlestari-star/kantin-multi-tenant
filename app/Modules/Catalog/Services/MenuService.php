<?php

namespace App\Modules\Catalog\Services;

use App\Models\Menu;
use Illuminate\Support\Str;

class MenuService
{
    public function create(array $data): Menu
    {
        return Menu::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'price_amount' => $data['price_amount'],
            'stock_qty' => $data['stock_qty'] ?? 0,
            'is_available' => $data['is_available'] ?? true,
            'prep_minutes' => $data['prep_minutes'] ?? 0,
        ]);
    }

    public function update(Menu $menu, array $data): Menu
    {
        $menu->update([
            'category_id' => $data['category_id'] ?? $menu->category_id,
            'name' => $data['name'] ?? $menu->name,
            'slug' => isset($data['name'])
                ? Str::slug($data['name'])
                : $menu->slug,
            'price_amount' => $data['price_amount'] ?? $menu->price_amount,
            'stock_qty' => $data['stock_qty'] ?? $menu->stock_qty,
            'is_available' => $data['is_available'] ?? $menu->is_available,
            'prep_minutes' => $data['prep_minutes'] ?? $menu->prep_minutes,
        ]);

        return $menu->refresh();
    }

    public function deactivate(Menu $menu): Menu
    {
        $menu->update([
            'is_available' => false,
        ]);

        return $menu->refresh();
    }
}
