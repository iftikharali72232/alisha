<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopSliderController extends Controller
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

        $sliders = $shop->sliders()->orderBy('order')->paginate(12);

        return view('user.shop.sliders.index', compact('shop', 'sliders'));
    }

    public function store(Request $request)
    {
        $shop = $this->getUserShop();
        
        if (!$shop) {
            return redirect()->route('user.shop.create');
        }

        // Check plan limits
        $currentPlan = $shop->activeSubscription?->plan;
        if ($currentPlan && $shop->sliders()->count() >= $currentPlan->max_sliders) {
            return redirect()->back()->with('error', 'You have reached the maximum number of sliders allowed by your current plan. Please upgrade your plan to add more sliders.');
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'text_color' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['shop_id'] = $shop->id;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('shops/' . $shop->id . '/sliders', 'public');
        }

        ShopSlider::create($validated);

        return redirect()->route('user.shop.sliders.index')
            ->with('success', 'Slider created successfully!');
    }

    public function update(Request $request, ShopSlider $slider)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $slider->shop_id !== $shop->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'text_color' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $validated['image'] = $request->file('image')
                ->store('shops/' . $shop->id . '/sliders', 'public');
        }

        $slider->update($validated);

        return redirect()->route('user.shop.sliders.index')
            ->with('success', 'Slider updated successfully!');
    }

    public function destroy(ShopSlider $slider)
    {
        $shop = $this->getUserShop();
        
        if (!$shop || $slider->shop_id !== $shop->id) {
            abort(404);
        }

        // Delete image
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('user.shop.sliders.index')
            ->with('success', 'Slider deleted successfully!');
    }
}
