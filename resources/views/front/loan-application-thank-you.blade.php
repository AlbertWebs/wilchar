@extends('front.master')

@section('content')
<!-- Thank You Section Start -->
<section class="section py-5" style="background: #f8f9fa; min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xxl-7">
                <div class="text-center wow fadeInUp" data-wow-duration="0.8s">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
                    </div>
                    <h1 class="display-4 mb-3">Thank You!</h1>
                    <h2 class="h3 mb-4 text-muted">Your Loan Application Has Been Submitted Successfully</h2>
                    
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Application Received!</h4>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="card mt-4 mb-4">
                        <div class="card-body">
                            <h5 class="card-title">What's Next?</h5>
                            <p class="card-text">Our team will review your application and contact you within 2-3 business days. Please ensure your contact information is correct and you're available to take our call.</p>
                            <hr>
                            <p class="mb-0"><strong>Need Help?</strong></p>
                            <p class="mb-2">Contact us at:</p>
                            <p class="mb-1"><i class="bi bi-telephone"></i> <a href="tel:+254787666661">+254787666661 / 0793793362</a></p>
                            <p class="mb-1"><i class="bi bi-envelope"></i> <a href="mailto:willingtonochieng92@gmail.com">willingtonochieng92@gmail.com</a></p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn_theme btn_theme_active">
                            Return to Home <i class="bi bi-arrow-left"></i><span></span>
                        </a>
                        <a href="{{ route('loan-application.create') }}" class="btn_theme ms-3">
                            Submit Another Application <i class="bi bi-plus-circle"></i><span></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Thank You Section End -->
@endsection
