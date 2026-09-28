<?php

namespace App\Modules\Catalog\Controllers;

use App\Models\Menu;
use App\Modules\Catalog\Services\MenuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController
{
    public function __construct(
        private readonly MenuService $menuService,
    ) {}

    public function index(): View
    {
        $menus = Menu::with('category')
            ->orderBy('name')
            ->get();

        return view('catalog::menus.index', compact('menus'));
    }

    public function create(): View
    {
        return view('catalog::menus.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:120'],
            'price_amount' => ['required', 'numeric', 'min:0'],
            'stock_qty' => ['nullable', 'integer', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'prep_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        $this->menuService->create($validated);

        return redirect()
            ->route('tenant.menus.index', [
                'tenant' => $request->route('tenant'),
            ])
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'name' => ['nullable', 'string', 'max:120'],
            'price_amount' => ['nullable', 'numeric', 'min:0'],
            'stock_qty' => ['nullable', 'integer', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'prep_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        $this->menuService->update($menu, $validated);

        return back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function deactivate(Menu $menu): RedirectResponse
    {
        $this->menuService->deactivate($menu);

        return back()->with('success', 'Menu berhasil dinonaktifkan.');
    }
}
