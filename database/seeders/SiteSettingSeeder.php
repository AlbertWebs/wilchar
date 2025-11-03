<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Wilchar Loan Management System',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Site Name',
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Your Trusted Financial Partner',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Site Tagline',
            ],
            [
                'key' => 'site_email',
                'value' => 'info@wilchar.com',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Contact Email',
            ],
            [
                'key' => 'site_phone',
                'value' => '+254 700 000 000',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Contact Phone',
            ],
            [
                'key' => 'site_address',
                'value' => '123 Main Street, Nairobi, Kenya',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Site Address',
            ],
            [
                'key' => 'site_location',
                'value' => 'Nairobi, Kenya',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Site Location',
            ],
            [
                'key' => 'footer_text',
                'value' => '© ' . date('Y') . ' Wilchar Loan Management System. All rights reserved.',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Footer Text',
            ],

            // M-Pesa Settings
            [
                'key' => 'paybill_number',
                'value' => '000000',
                'type' => 'text',
                'group' => 'mpesa',
                'description' => 'M-Pesa PayBill Number',
            ],
            [
                'key' => 'account_number',
                'value' => 'WILCHAR',
                'type' => 'text',
                'group' => 'mpesa',
                'description' => 'M-Pesa Account Number',
            ],
            [
                'key' => 'till_number',
                'value' => '000000',
                'type' => 'text',
                'group' => 'mpesa',
                'description' => 'M-Pesa Till Number',
            ],

            // Social Media Links
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/wilchar',
                'type' => 'url',
                'group' => 'social',
                'description' => 'Facebook URL',
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/wilchar',
                'type' => 'url',
                'group' => 'social',
                'description' => 'Twitter URL',
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/wilchar',
                'type' => 'url',
                'group' => 'social',
                'description' => 'Instagram URL',
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com/company/wilchar',
                'type' => 'url',
                'group' => 'social',
                'description' => 'LinkedIn URL',
            ],
            [
                'key' => 'youtube_url',
                'value' => 'https://youtube.com/@wilchar',
                'type' => 'url',
                'group' => 'social',
                'description' => 'YouTube URL',
            ],
            [
                'key' => 'whatsapp_number',
                'value' => '254700000000',
                'type' => 'text',
                'group' => 'social',
                'description' => 'WhatsApp Business Number',
            ],

            // SEO Settings
            [
                'key' => 'meta_title',
                'value' => 'Wilchar Loan Management System - Your Trusted Financial Partner',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta Title',
            ],
            [
                'key' => 'meta_description',
                'value' => 'Wilchar offers comprehensive loan management solutions with flexible repayment options and excellent customer service.',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta Description',
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'loans, loan management, financial services, microloans, Kenya',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta Keywords',
            ],

            // Business Settings
            [
                'key' => 'business_hours',
                'value' => 'Monday - Friday: 8:00 AM - 5:00 PM | Saturday: 9:00 AM - 1:00 PM | Sunday: Closed',
                'type' => 'text',
                'group' => 'business',
                'description' => 'Business Hours',
            ],
            [
                'key' => 'currency',
                'value' => 'KES',
                'type' => 'text',
                'group' => 'business',
                'description' => 'Default Currency',
            ],
            [
                'key' => 'currency_symbol',
                'value' => 'KES',
                'type' => 'text',
                'group' => 'business',
                'description' => 'Currency Symbol',
            ],
            [
                'key' => 'tax_rate',
                'value' => '0',
                'type' => 'text',
                'group' => 'business',
                'description' => 'Tax Rate (%)',
            ],

            // Legal & Policies
            [
                'key' => 'terms_url',
                'value' => '/terms-and-conditions',
                'type' => 'url',
                'group' => 'legal',
                'description' => 'Terms and Conditions URL',
            ],
            [
                'key' => 'privacy_policy_url',
                'value' => '/privacy-policy',
                'type' => 'url',
                'group' => 'legal',
                'description' => 'Privacy Policy URL',
            ],
            [
                'key' => 'refund_policy_url',
                'value' => '/refund-policy',
                'type' => 'url',
                'group' => 'legal',
                'description' => 'Refund Policy URL',
            ],

            // Branding (empty by default, user will upload)
            [
                'key' => 'logo',
                'value' => null,
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Main Logo',
            ],
            [
                'key' => 'logo_dark',
                'value' => null,
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Dark Mode Logo',
            ],
            [
                'key' => 'favicon',
                'value' => null,
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Favicon',
            ],

            // Additional Contact Information
            [
                'key' => 'contact_email_2',
                'value' => 'support@wilchar.com',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Support Email',
            ],
            [
                'key' => 'contact_phone_2',
                'value' => '+254 711 000 000',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Support Phone',
            ],
            [
                'key' => 'pobox',
                'value' => 'P.O. Box 12345, Nairobi',
                'type' => 'text',
                'group' => 'general',
                'description' => 'P.O. Box',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'description' => $setting['description'],
                ]
            );
        }
    }
}