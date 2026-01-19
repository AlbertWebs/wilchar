<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalPageController extends Controller
{
    private $pageTypes = [
        'terms' => [
            'title' => 'Terms and Conditions',
            'slug' => 'terms-and-conditions',
            'key' => 'terms',
        ],
        'privacy' => [
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'key' => 'privacy',
        ],
        'copyright' => [
            'title' => 'Copyright Statement',
            'slug' => 'copyright-statement',
            'key' => 'copyright',
        ],
        'cbk-disclaimer' => [
            'title' => 'CBK Disclaimer',
            'slug' => 'cbk-disclaimer',
            'key' => 'cbk-disclaimer',
        ],
    ];

    /**
     * Display a listing of legal pages
     */
    public function index()
    {
        $pages = [];
        foreach ($this->pageTypes as $key => $type) {
            $page = Page::where('slug', $type['slug'])->first();
            $pages[$key] = [
                'type' => $type,
                'page' => $page,
                'exists' => $page !== null,
            ];
        }

        return view('admin.legal-pages.index', compact('pages'));
    }

    /**
     * Show the form for editing a legal page
     */
    public function edit($type)
    {
        if (!isset($this->pageTypes[$type])) {
            abort(404);
        }

        $pageType = $this->pageTypes[$type];
        $page = Page::where('slug', $pageType['slug'])->first();

        // Create page if it doesn't exist
        if (!$page) {
            $page = Page::create([
                'title' => $pageType['title'],
                'slug' => $pageType['slug'],
                'content' => $this->getDefaultContent($type),
                'status' => 'published',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'published_at' => now(),
            ]);
        }

        return view('admin.legal-pages.edit', compact('page', 'type', 'pageType'));
    }

    /**
     * Update a legal page
     */
    public function update(Request $request, $type)
    {
        if (!isset($this->pageTypes[$type])) {
            abort(404);
        }

        $pageType = $this->pageTypes[$type];
        $page = Page::where('slug', $pageType['slug'])->firstOrFail();

        $validated = $request->validate([
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $page->update([
            'content' => $validated['content'],
            'meta_title' => $validated['meta_title'] ?? $pageType['title'],
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.legal-pages.index')
            ->with('success', $pageType['title'] . ' updated successfully.');
    }

    /**
     * Get default content for each page type
     */
    private function getDefaultContent($type)
    {
        $defaults = [
            'terms' => '<h2>Terms and Conditions</h2>
<p>Last updated: ' . now()->format('F d, Y') . '</p>

<h3>1. Introduction</h3>
<p>Welcome to Nuru Wilchar SME Capital Limited. These Terms and Conditions govern your use of our services and website. By accessing our services, you agree to be bound by these terms.</p>

<h3>2. Loan Services</h3>
<p>We provide business loans and cash advances to small and medium enterprises. All loans are subject to our approval process and terms.</p>

<h3>3. Repayment Terms</h3>
<p>All loans must be repaid according to the agreed schedule. Late payments may incur additional charges.</p>

<h3>4. Interest Rates</h3>
<p>Interest rates vary based on loan duration and amount. Please refer to your loan agreement for specific rates.</p>

<h3>5. Contact Information</h3>
<p>For questions about these terms, please contact us at the information provided on our website.</p>',

            'privacy' => '<h2>Privacy Policy</h2>
<p>Last updated: ' . now()->format('F d, Y') . '</p>

<h3>1. Information We Collect</h3>
<p>We collect information that you provide directly to us, including personal identification information, financial information, and business details.</p>

<h3>2. How We Use Your Information</h3>
<p>We use your information to process loan applications, manage accounts, communicate with you, and comply with legal obligations.</p>

<h3>3. Information Sharing</h3>
<p>We do not sell your personal information. We may share information with service providers, regulatory authorities, and as required by law.</p>

<h3>4. Data Security</h3>
<p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, or disclosure.</p>

<h3>5. Your Rights</h3>
<p>You have the right to access, correct, or delete your personal information. Contact us to exercise these rights.</p>

<h3>6. Contact Us</h3>
<p>For privacy concerns, please contact us using the information provided on our website.</p>',

            'copyright' => '<h2>Copyright Statement</h2>
<p>Last updated: ' . now()->format('F d, Y') . '</p>

<h3>Copyright Ownership</h3>
<p>All content on this website, including text, graphics, logos, images, and software, is the property of Nuru Wilchar SME Capital Limited and is protected by copyright laws.</p>

<h3>Use of Materials</h3>
<p>You may not reproduce, distribute, modify, or create derivative works from any content on this website without our express written permission.</p>

<h3>Trademarks</h3>
<p>All trademarks, service marks, and trade names used on this website are the property of Nuru Wilchar SME Capital Limited or their respective owners.</p>

<h3>Permissions</h3>
<p>For permission to use any content from this website, please contact us at the information provided on our website.</p>',

            'cbk-disclaimer' => '<h2>CBK Disclaimer</h2>
<p>Last updated: ' . now()->format('F d, Y') . '</p>

<h3>Regulatory Compliance</h3>
<p>Nuru Wilchar SME Capital Limited operates in compliance with the regulations set forth by the Central Bank of Kenya (CBK) and other relevant regulatory authorities.</p>

<h3>Licensing</h3>
<p>We are committed to operating within the legal framework established by the Central Bank of Kenya for financial service providers.</p>

<h3>Consumer Protection</h3>
<p>We adhere to all consumer protection guidelines and regulations as mandated by the Central Bank of Kenya.</p>

<h3>Dispute Resolution</h3>
<p>Any disputes will be resolved in accordance with Kenyan law and the regulations of the Central Bank of Kenya.</p>

<h3>Contact Information</h3>
<p>For regulatory inquiries or concerns, please contact us or the Central Bank of Kenya directly.</p>

<p><strong>Central Bank of Kenya</strong><br>
Haile Selassie Avenue<br>
Nairobi, Kenya<br>
Website: <a href="https://www.centralbank.go.ke" target="_blank">www.centralbank.go.ke</a></p>',
        ];

        return $defaults[$type] ?? '<p>Content coming soon...</p>';
    }
}
