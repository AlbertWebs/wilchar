<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::active()->ordered()->get();
        $products = Product::active()->ordered()->limit(6)->get();
        
        // SEO Meta Data
        $metaTitle = 'Nuru Wilchar SME Capital Limited - Empowering Small Businesses with Quick Loans';
        $metaDescription = 'Get fast business loans and cash advances for your SME. Nuru Wilchar SME Capital provides financial support to small businesses in rural and peri-urban areas across Western Region, Kenya. Apply online today!';
        $metaKeywords = 'business loans Kenya, SME loans, quick loans, cash advances, small business financing, Western Region loans, business capital, online loan application, Nuru Wilchar';
        $ogImage = asset('main/assets/images/hero_img2.png');
        $canonicalUrl = route('home');
        
        return view('front.index', compact('testimonials', 'products', 'metaTitle', 'metaDescription', 'metaKeywords', 'ogImage', 'canonicalUrl'));
    }
}
