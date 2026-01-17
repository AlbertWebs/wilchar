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
            'paybill_number' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'home_hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'home_why_choose_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'home_how_works_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'home_about_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'home_loan_solution_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'home_title_vector' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:512',
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
        $this->updateSetting('paybill_number', $request->paybill_number, 'general', 'M-Pesa Paybill Number');

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

        // Home Page Section Images
        if ($request->hasFile('home_hero_image')) {
            $heroPath = $request->file('home_hero_image')->store('settings', 'public');
            $this->updateSetting('home_hero_image', $heroPath, 'home_images', 'Hero Section Image', 'image');
        }

        if ($request->hasFile('home_why_choose_image')) {
            $whyChoosePath = $request->file('home_why_choose_image')->store('settings', 'public');
            $this->updateSetting('home_why_choose_image', $whyChoosePath, 'home_images', 'Why Choose Us Image', 'image');
        }

        if ($request->hasFile('home_how_works_image')) {
            $howWorksPath = $request->file('home_how_works_image')->store('settings', 'public');
            $this->updateSetting('home_how_works_image', $howWorksPath, 'home_images', 'How It Works Image', 'image');
        }

        if ($request->hasFile('home_about_image')) {
            $aboutPath = $request->file('home_about_image')->store('settings', 'public');
            $this->updateSetting('home_about_image', $aboutPath, 'home_images', 'About Us Image', 'image');
        }

        if ($request->hasFile('home_loan_solution_image')) {
            $loanSolutionPath = $request->file('home_loan_solution_image')->store('settings', 'public');
            $this->updateSetting('home_loan_solution_image', $loanSolutionPath, 'home_images', 'Loan Solution Image', 'image');
        }

        if ($request->hasFile('home_title_vector')) {
            $titleVectorPath = $request->file('home_title_vector')->store('settings', 'public');
            $this->updateSetting('home_title_vector', $titleVectorPath, 'home_images', 'Title Vector Icon', 'image');
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
                'paybill_number' => SiteSetting::getValue('paybill_number', ''),
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
            'home_images' => [
                'home_hero_image' => SiteSetting::getValue('home_hero_image', ''),
                'home_why_choose_image' => SiteSetting::getValue('home_why_choose_image', ''),
                'home_how_works_image' => SiteSetting::getValue('home_how_works_image', ''),
                'home_about_image' => SiteSetting::getValue('home_about_image', ''),
                'home_loan_solution_image' => SiteSetting::getValue('home_loan_solution_image', ''),
                'home_title_vector' => SiteSetting::getValue('home_title_vector', ''),
            ],
        ];
    }
}