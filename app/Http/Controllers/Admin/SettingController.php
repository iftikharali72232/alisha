<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'footer_text' => 'nullable|string|max:500',
            'google_analytics' => 'nullable|string|max:100',
            'posts_per_page' => 'nullable|integer|min:1|max:50',
        ]);

        foreach ($validated as $key => $value) {
            if ($key === 'logo' || $key === 'favicon') {
                continue;
            }
            Setting::set($key, $value);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $logoPath);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::get('favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $faviconPath);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
