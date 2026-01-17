@extends('front.master')

@section('content')
    <!-- Products Page Hero -->
    <section class="hero-section hero--secondary" style="padding: 120px 0 80px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8 mx-auto text-center">
                    <h1 class="hero--secondary__title wow fadeInUp" data-wow-duration="0.8s">Our Products & Solutions</h1>
                    <p class="hero--secondary__text wow fadeInDown" data-wow-duration="0.8s">Discover our comprehensive range of loan products designed to meet your business needs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="section" style="padding: 80px 0;">
        <div class="container">
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card card--custom wow fadeInUp" data-wow-duration="0.8s" style="height: 100%; transition: all 0.3s ease;">
                                @if($product->image)
                                    <div class="card__thumb" style="height: 200px; overflow: hidden; border-radius: 12px 12px 0 0;">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @endif
                                <div class="card__content" style="padding: 1.5rem;">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        @if($product->icon)
                                            <img src="{{ asset('storage/' . $product->icon) }}" alt="{{ $product->name }}" style="width: 48px; height: 48px; object-fit: contain;">
                                        @endif
                                        <h4 class="card__title mb-0">{{ $product->name }}</h4>
                                    </div>
                                    
                                    @if($product->short_description)
                                        <p class="fs-small mb-3" style="color: #64748b;">{{ $product->short_description }}</p>
                                    @endif

                                    @if($product->features && count($product->features) > 0)
                                        <ul class="mb-3" style="list-style: none; padding: 0;">
                                            @foreach(array_slice($product->features, 0, 3) as $feature)
                                                <li class="mb-2" style="color: #475569;">
                                                    <i class="bi bi-check-circle-fill text-emerald-500 me-2"></i>{{ $feature }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @if($product->interest_rate)
                                            <span class="badge" style="background: #f0f9ff; color: #0ea5e9; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                                                <i class="bi bi-percent"></i> {{ $product->interest_rate }}% Rate
                                            </span>
                                        @endif
                                        @if($product->loan_duration)
                                            <span class="badge" style="background: #fef3c7; color: #f59e0b; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem;">
                                                <i class="bi bi-calendar-week"></i> {{ $product->loan_duration }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($product->min_amount || $product->max_amount)
                                        <p class="mb-3" style="color: #1e293b; font-weight: 600;">
                                            <i class="bi bi-currency-exchange me-2"></i>
                                            KES {{ number_format($product->min_amount ?? 0, 0) }} - 
                                            KES {{ number_format($product->max_amount ?? 0, 0) }}
                                        </p>
                                    @endif

                                    <a href="{{ route('products.show', $product->slug) }}" class="btn_theme btn_theme_active w-100 text-center">
                                        Learn More <i class="bi bi-arrow-up-right"></i><span></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-inbox" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h3 class="mb-2">No Products Available</h3>
                    <p class="text-slate-500">Products will be displayed here once they are added by the administrator.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); padding: 80px 0; border-radius: 0;">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 mx-auto text-center text-white">
                    <h2 class="mb-3">Ready to Get Started?</h2>
                    <p class="mb-4">Apply for a loan today and get the financial support your business needs.</p>
                    <a href="{{ route('loan-application.create') }}" class="btn_theme" style="background: white; color: #0ea5e9;">
                        Apply Now <i class="bi bi-arrow-up-right"></i><span></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
