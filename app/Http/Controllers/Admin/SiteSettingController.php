<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    /**
     * Display site settings edit form
     */
    public function edit()
    {
        $settings = SiteSetting::all()->groupBy('group');
        
        // Get or create default settings
        $defaultSettings = $this->getDefaultSettings();
        
        return view('admin.site-settings.edit', compact('settings', 'defaultSettings'));
    }

    /**
     * Update site settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:255',
            'site_phone_alt' => 'nullable|string|max:255',
            'site_address' => 'nullable|string|max:500',
            'site_address_alt' => 'nullable|string|max:500',
            'site_location' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
            'footer_description' => 'nullable|string|max:500',
            'footer_text' => 'nullable|string|max:500',
            'footer_powered_by' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:255',
            'telegram_url' => 'nullable|url|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        // General Settings
        $this->updateSetting('site_name', $request->site_name, 'general', 'Site Name');
        $this->updateSetting('site_tagline', $request->site_tagline, 'general', 'Site Tagline');
        $this->updateSetting('site_email', $request->site_email, 'general', 'Contact Email');
        $this->updateSetting('site_phone', $request->site_phone, 'general', 'Contact Phone');
        $this->updateSetting('site_phone_alt', $request->site_phone_alt, 'general', 'Alternate Contact Phone');
        $this->updateSetting('site_address', $request->site_address, 'general', 'Site Address');
        $this->updateSetting('site_address_alt', $request->site_address_alt, 'general', 'Alternate Site Address');
        $this->updateSetting('site_location', $request->site_location, 'general', 'Site Location');
        $this->updateSetting('footer_description', $request->footer_description, 'general', 'Footer Description');
        $this->updateSetting('footer_text', $request->footer_text, 'general', 'Footer Copyright Text');
        $this->updateSetting('footer_powered_by', $request->footer_powered_by, 'general', 'Footer Powered By Text');

        // Branding (Images)
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            $this->updateSetting('logo', $logoPath, 'branding', 'Main Logo', 'image');
        }

        if ($request->hasFile('logo_dark')) {
            $logoDarkPath = $request->file('logo_dark')->store('settings', 'public');
            $this->updateSetting('logo_dark', $logoDarkPath, 'branding', 'Dark Mode Logo', 'image');
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $this->updateSetting('favicon', $faviconPath, 'branding', 'Favicon', 'image');
        }

        // Social Media
        $this->updateSetting('facebook_url', $request->facebook_url, 'social', 'Facebook URL');
        $this->updateSetting('twitter_url', $request->twitter_url, 'social', 'Twitter URL');
        $this->updateSetting('instagram_url', $request->instagram_url, 'social', 'Instagram URL');
        $this->updateSetting('linkedin_url', $request->linkedin_url, 'social', 'LinkedIn URL');
        $this->updateSetting('youtube_url', $request->youtube_url, 'social', 'YouTube URL');
        $this->updateSetting('whatsapp_number', $request->whatsapp_number, 'social', 'WhatsApp Business Number');
        $this->updateSetting('telegram_url', $request->telegram_url, 'social', 'Telegram URL');

        // SEO
        $this->updateSetting('meta_title', $request->meta_title, 'seo', 'Meta Title');
        $this->updateSetting('meta_description', $request->meta_description, 'seo', 'Meta Description');
        $this->updateSetting('meta_keywords', $request->meta_keywords, 'seo', 'Meta Keywords');

        return redirect()->route('admin.site-settings.edit')
            ->with('success', 'Site settings updated successfully!');
    }

    /**
     * Helper method to update a setting
     */
    private function updateSetting(string $key, $value, string $group, string $description = null, string $type = 'text')
    {
        // If removing an image, delete the old one
        if ($type === 'image' && $value === null) {
            $existing = SiteSetting::where('key', $key)->first();
            if ($existing && $existing->value) {
                Storage::disk('public')->delete($existing->value);
            }
        } else if ($type === 'image' && $value !== null) {
            // Delete old image if updating
            $existing = SiteSetting::where('key', $key)->first();
            if ($existing && $existing->value && $existing->value !== $value) {
                Storage::disk('public')->delete($existing->value);
            }
        }

        SiteSetting::setValue($key, $value, $type, $group, $description);
    }

    /**
     * Get default settings structure
     */
    private function getDefaultSettings()
    {
        return [
            'general' => [
                'site_name' => SiteSetting::getValue('site_name', config('app.name')),
                'site_tagline' => SiteSetting::getValue('site_tagline', ''),
                'site_email' => SiteSetting::getValue('site_email', ''),
                'site_phone' => SiteSetting::getValue('site_phone', ''),
                'site_phone_alt' => SiteSetting::getValue('site_phone_alt', ''),
                'site_address' => SiteSetting::getValue('site_address', ''),
                'site_address_alt' => SiteSetting::getValue('site_address_alt', ''),
                'site_location' => SiteSetting::getValue('site_location', ''),
                'footer_description' => SiteSetting::getValue('footer_description', ''),
                'footer_text' => SiteSetting::getValue('footer_text', ''),
                'footer_powered_by' => SiteSetting::getValue('footer_powered_by', ''),
            ],
            'branding' => [
                'logo' => SiteSetting::getValue('logo', ''),
                'logo_dark' => SiteSetting::getValue('logo_dark', ''),
                'favicon' => SiteSetting::getValue('favicon', ''),
            ],
            'social' => [
                'facebook_url' => SiteSetting::getValue('facebook_url', ''),
                'twitter_url' => SiteSetting::getValue('twitter_url', ''),
                'instagram_url' => SiteSetting::getValue('instagram_url', ''),
                'linkedin_url' => SiteSetting::getValue('linkedin_url', ''),
                'youtube_url' => SiteSetting::getValue('youtube_url', ''),
                'whatsapp_number' => SiteSetting::getValue('whatsapp_number', ''),
                'telegram_url' => SiteSetting::getValue('telegram_url', ''),
            ],
            'seo' => [
                'meta_title' => SiteSetting::getValue('meta_title', ''),
                'meta_description' => SiteSetting::getValue('meta_description', ''),
                'meta_keywords' => SiteSetting::getValue('meta_keywords', ''),
            ],
        ];
    }
}