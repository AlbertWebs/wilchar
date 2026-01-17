<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index()
    {
        $products = Product::active()->ordered()->get();
        
        // SEO Meta Data
        $metaTitle = 'Our Loan Products & Solutions - Nuru Wilchar SME Capital';
        $metaDescription = 'Explore our comprehensive range of loan products designed for small businesses. From quick cash advances to extended business loans, find the perfect financing solution for your SME needs.';
        $metaKeywords = 'loan products, business loan solutions, SME financing options, cash advance products, business loan types, Kenya business loans';
        $ogImage = asset('main/assets/images/hero_img2.png');
        $canonicalUrl = route('products.index');
        
        return view('front.products.index', compact('products', 'metaTitle', 'metaDescription', 'metaKeywords', 'ogImage', 'canonicalUrl'));
    }

    /**
     * Display a single product
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->ordered()
            ->limit(3)
            ->get();
        
        // SEO Meta Data
        $metaTitle = $product->name . ' - Nuru Wilchar SME Capital';
        $metaDescription = $product->short_description ?? $product->description ?? 'Learn more about ' . $product->name . ' from Nuru Wilchar SME Capital. Get the financial support your business needs.';
        $metaKeywords = $product->name . ', business loans, SME financing, ' . ($product->loan_duration ?? '') . ', Kenya loans';
        $ogImage = $product->image ? asset('storage/' . $product->image) : asset('main/assets/images/hero_img2.png');
        $canonicalUrl = route('products.show', $product->slug);
        
        return view('front.products.show', compact('product', 'relatedProducts', 'metaTitle', 'metaDescription', 'metaKeywords', 'ogImage', 'canonicalUrl'));
    }
}
