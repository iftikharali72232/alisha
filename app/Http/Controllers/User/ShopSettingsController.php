<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopSettingsController extends Controller
{
    private function getUserShop()
    {
        return Shop::where('user_id', Auth::id())->first();
    }

    public function index()
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        return view('user.shop.settings.index', compact('shop'));
    }

    public function update(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tagline' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url|max:255',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'shipping_fee' => 'nullable|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'primary_color' => ['nullable','regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color' => ['nullable','regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:500',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
        ]);

        // Prevent unauthorized color customization if plan doesn't allow it
        if (! $shop->canCustomizeTheme()) {
            // Remove color fields if present in the request so they are not saved
            unset($validated['primary_color'], $validated['secondary_color']);
        }

        // Ensure storage directories exist
        Storage::disk('public')->makeDirectory('shops/logos');
        Storage::disk('public')->makeDirectory('shops/banners');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            $validated['logo'] = $request->file('logo')
                ->store('shops/logos', 'public');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            if ($shop->banner) {
                Storage::disk('public')->delete($shop->banner);
            }
            $validated['banner'] = $request->file('banner')
                ->store('shops/banners', 'public');
        }

        // Update slug if name changed
        if ($validated['name'] !== $shop->name) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        }

        // Clean empty social links
        if (isset($validated['social_links'])) {
            $validated['social_links'] = array_filter($validated['social_links']);
        }

        // track if colors were attempted but blocked
        $blockedColors = ($request->hasAny(['primary_color','secondary_color']) && ! $shop->canCustomizeTheme());

        $shop->update($validated);

        $redirect = redirect()->route('user.shop.settings.index')
            ->with('success', 'Settings updated successfully!');

        if ($blockedColors) {
            $redirect = $redirect->with('info', 'Theme customization is available only on Professional and Premium plans. Color changes were not saved.');
        }

        return $redirect;
    }
}
