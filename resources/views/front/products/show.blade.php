@extends('front.master')

@section('content')
    <!-- Product Detail Hero -->
    <section class="hero-section hero--secondary banner" style="padding: 120px 0 80px; background-color: #03211B; position: relative;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8 mx-auto text-center">
                    <h1 class="hero--secondary__title wow fadeInUp" data-wow-duration="0.8s" style="color: #ffffff;">{{ $product->name }}</h1>
                    @if($product->short_description)
                        <p class="hero--secondary__text wow fadeInDown" data-wow-duration="0.8s" style="color: rgba(255, 255, 255, 0.9);">{{ $product->short_description }}</p>
                    @endif
                    <nav aria-label="breadcrumb" class="mt-4 wow fadeInDown" data-wow-duration="0.8s">
                        <ol class="breadcrumb justify-content-center" style="margin-bottom: 0;">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #ffffff;">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" style="color: #ffffff;">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #FCB650;">{{ $product->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Details -->
    <section class="section" style="padding: 80px 0;">
        <div class="container">
            <div class="row g-5">
                <div class="col-12 col-lg-8">
                    @if($product->image)
                        <div class="mb-4 wow fadeInUp" data-wow-duration="0.8s">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-100 rounded-3" style="max-height: 400px; object-fit: cover;">
                        </div>
                    @endif

                    <div class="card card--custom wow fadeInUp" data-wow-duration="0.8s">
                        <div class="card__content" style="padding: 2rem;">
                            <h3 class="mb-4">About This Product</h3>
                            <div style="color: #475569; line-height: 1.8;">
                                {!! $product->description ?? '<p>No description available.</p>' !!}
                            </div>
                        </div>
                    </div>

                    @if($product->features && count($product->features) > 0)
                        <div class="card card--custom mt-4 wow fadeInUp" data-wow-duration="0.8s">
                            <div class="card__content" style="padding: 2rem;">
                                <h3 class="mb-4">Key Features</h3>
                                <div class="row g-3">
                                    @foreach($product->features as $feature)
                                        <div class="col-12 col-md-6">
                                            <div class="d-flex align-items-start gap-2">
                                                <i class="bi bi-check-circle-fill text-emerald-500 mt-1" style="font-size: 1.25rem;"></i>
                                                <span style="color: #475569;">{{ $feature }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card card--custom wow fadeInDown" data-wow-duration="0.8s" style="position: sticky; top: 100px;">
                        <div class="card__content" style="padding: 2rem;">
                            <h4 class="mb-4">Product Details</h4>
                            
                            @if($product->interest_rate)
                                <div class="mb-3 pb-3 border-bottom">
                                    <p class="mb-1" style="color: #64748b; font-size: 0.875rem;">Interest Rate</p>
                                    <p class="mb-0" style="color: #1e293b; font-size: 1.5rem; font-weight: 600;">{{ $product->interest_rate }}%</p>
                                </div>
                            @endif

                            @if($product->loan_duration)
                                <div class="mb-3 pb-3 border-bottom">
                                    <p class="mb-1" style="color: #64748b; font-size: 0.875rem;">Loan Duration</p>
                                    <p class="mb-0" style="color: #1e293b; font-size: 1.25rem; font-weight: 600;">{{ $product->loan_duration }}</p>
                                </div>
                            @endif

                            @if($product->min_amount || $product->max_amount)
                                <div class="mb-3 pb-3 border-bottom">
                                    <p class="mb-1" style="color: #64748b; font-size: 0.875rem;">Loan Amount Range</p>
                                    <p class="mb-0" style="color: #1e293b; font-size: 1.25rem; font-weight: 600;">
                                        KES {{ number_format($product->min_amount ?? 0, 0) }} - 
                                        KES {{ number_format($product->max_amount ?? 0, 0) }}
                                    </p>
                                </div>
                            @endif

                            <a href="{{ route('loan-application.create') }}" class="btn_theme btn_theme_active w-100 text-center mt-4">
                                Apply for This Product <i class="bi bi-arrow-up-right"></i><span></span>
                            </a>
                            <a href="{{ route('products.index') }}" class="btn_theme w-100 text-center mt-2" style="background: transparent; border: 2px solid #0ea5e9; color: #0ea5e9;">
                                View All Products <i class="bi bi-arrow-left"></i><span></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($relatedProducts->count() > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="mb-4">Related Products</h3>
                        <div class="row g-4">
                            @foreach($relatedProducts as $related)
                                <div class="col-12 col-md-4">
                                    <div class="card card--custom">
                                        <div class="card__content" style="padding: 1.5rem;">
                                            <h5 class="card__title mb-2">{{ $related->name }}</h5>
                                            @if($related->short_description)
                                                <p class="fs-small mb-3" style="color: #64748b;">{{ Str::limit($related->short_description, 100) }}</p>
                                            @endif
                                            <a href="{{ route('products.show', $related->slug) }}" class="btn_theme">
                                                Learn More <i class="bi bi-arrow-up-right"></i><span></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
