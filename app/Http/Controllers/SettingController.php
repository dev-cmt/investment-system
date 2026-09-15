<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the settings edit form.
     */
    public function edit(): View
    {
        $setting = Setting::getSiteSettings();
        return view('backend.settings.edit', compact('setting'));
    }

    /**
     * Update site settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name'   => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'email2'         => 'nullable|email|max:255',
            'alert_email'    => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:30',
            'phone2'         => 'nullable|string|max:30',
            'address'        => 'nullable|string|max:500',
            'map_url'        => 'nullable|url|max:1000',
            'hero_video_url' => 'nullable|url|max:1000',
            'description'    => 'nullable|string|max:1000',
            'copyright_text' => 'nullable|string|max:255',
            'logo'           => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
            'logo_dark'      => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
            'logo_light'     => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
            'favicon'        => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,ico|max:512',
            'social_facebook'  => 'nullable|url|max:500',
            'social_twitter'   => 'nullable|url|max:500',
            'social_instagram' => 'nullable|url|max:500',
            'social_youtube'   => 'nullable|url|max:500',
            'social_linkedin'  => 'nullable|url|max:500',
            'social_whatsapp'  => 'nullable|string|max:30',
        ]);

        $setting = Setting::getSiteSettings();

        if ($request->hasFile('logo')) {
            $setting->logo = ImageHelper::uploadImage($request->file('logo'), 'uploads/settings', $setting->logo);
        }
        if ($request->hasFile('logo_dark')) {
            $setting->logo_dark = ImageHelper::uploadImage($request->file('logo_dark'), 'uploads/settings', $setting->logo_dark);
        }
        if ($request->hasFile('logo_light')) {
            $setting->logo_light = ImageHelper::uploadImage($request->file('logo_light'), 'uploads/settings', $setting->logo_light);
        }
        if ($request->hasFile('favicon')) {
            $setting->favicon = ImageHelper::uploadImage($request->file('favicon'), 'uploads/settings', $setting->favicon);
        }

        $socialLinks = [
            'facebook'  => $request->input('social_facebook', ''),
            'twitter'   => $request->input('social_twitter', ''),
            'instagram' => $request->input('social_instagram', ''),
            'youtube'   => $request->input('social_youtube', ''),
            'linkedin'  => $request->input('social_linkedin', ''),
            'whatsapp'  => $request->input('social_whatsapp', ''),
        ];

        $setting->company_name   = $request->company_name;
        $setting->email          = $request->email;
        $setting->email2         = $request->email2;
        $setting->alert_email    = $request->alert_email;
        $setting->phone          = $request->phone;
        $setting->phone2         = $request->phone2;
        $setting->address        = $request->address;
        $setting->map_url        = $request->map_url;
        $setting->hero_video_url = $request->hero_video_url;
        $setting->description    = $request->description;
        $setting->copyright_text = $request->copyright_text;
        $setting->social_links   = $socialLinks;

        $setting->save();

        return redirect()->route('settings.index')
            ->with('success', 'Site settings updated successfully!');
    }
}
