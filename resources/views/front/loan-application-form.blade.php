@extends('front.master')

@section('content')
<!-- Dark Header Section Start -->
<section class="py-5" style="background: #1b1b18; color: #fff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xxl-9">
                <div class="text-center wow fadeInUp" data-wow-duration="0.8s">
                    <h1 class="display-4 mb-3" style="color: #fff;">Loan Application Form</h1>
                    <p class="lead" style="color: #dbdbd7;">Fill out the form below to apply for a business loan with NURU WILCHAR SME CAPITAL LIMITED</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Dark Header Section End -->

<!-- Application Form Section Start -->
<section class="section py-5" style="background: #f8f9fa;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xxl-9">

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('loan-application.store') }}" method="POST" enctype="multipart/form-data" id="loanApplicationForm">
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div class="card mb-4 wow fadeInUp" data-wow-duration="0.8s">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="2547XXXXXXXX" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">M-Pesa Phone Number</label>
                                    <input type="tel" name="mpesa_phone" class="form-control" value="{{ old('mpesa_phone') }}" placeholder="2547XXXXXXXX">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Alternate Phone</label>
                                    <input type="tel" name="alternate_phone" class="form-control" value="{{ old('alternate_phone') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ID Number <span class="text-danger">*</span></label>
                                    <input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Select</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Information Section -->
                    <div class="card mb-4 wow fadeInUp" data-wow-duration="0.8s">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-building me-2"></i>Business Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Business Type <span class="text-danger">*</span></label>
                                    <select name="business_type" class="form-control" required>
                                        <option value="">Select Business Type</option>
                                        <option value="Mama Mboga" {{ old('business_type') == 'Mama Mboga' ? 'selected' : '' }}>Mama Mboga</option>
                                        <option value="Boda Boda" {{ old('business_type') == 'Boda Boda' ? 'selected' : '' }}>Boda Boda</option>
                                        <option value="Tuk-tuk Operator" {{ old('business_type') == 'Tuk-tuk Operator' ? 'selected' : '' }}>Tuk-tuk Operator</option>
                                        <option value="Small Shop" {{ old('business_type') == 'Small Shop' ? 'selected' : '' }}>Small Shop</option>
                                        <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Business Location <span class="text-danger">*</span></label>
                                    <input type="text" name="business_location" class="form-control" value="{{ old('business_location') }}" placeholder="Town, Estate, Street" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loan Details Section -->
                    <div class="card mb-4 wow fadeInUp" data-wow-duration="0.8s">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-currency-dollar me-2"></i>Loan Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Loan Product</label>
                                    <select name="loan_product_id" class="form-control" id="loan_product_id">
                                        <option value="">Select Product (Optional)</option>
                                        @foreach($loanProducts as $product)
                                            <option value="{{ $product->id }}" {{ old('loan_product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Loan Amount (KES) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="loan_amount" class="form-control" min="1000" step="0.01" value="{{ old('amount') }}" oninput="calculateTotals()" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Duration (Months) <span class="text-danger">*</span></label>
                                    <input type="number" name="duration_months" id="duration_months" class="form-control" min="1" max="120" value="{{ old('duration_months', 12) }}" oninput="calculateTotals()" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Repayment Frequency <span class="text-danger">*</span></label>
                                    <select name="repayment_frequency" id="repayment_frequency" class="form-control" onchange="applyFrequency()" required>
                                        @foreach($repaymentFrequencies as $key => $frequency)
                                            <option value="{{ $key }}" {{ old('repayment_frequency', 'monthly') == $key ? 'selected' : '' }}>
                                                {{ $frequency['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="repayment_interval_weeks" id="repayment_interval_weeks" value="4">
                                    <input type="hidden" name="interest_rate_type" value="frequency">
                                    <input type="hidden" name="interest_rate" id="interest_rate" value="10">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Registration/Processing Fee (KES)</label>
                                    <input type="number" name="registration_fee" id="registration_fee" class="form-control" min="0" step="0.01" value="{{ old('registration_fee', 0) }}" oninput="calculateTotals()">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Interest Rate (%)</label>
                                    <input type="number" id="interest_rate_display" class="form-control" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Loan Purpose</label>
                                    <textarea name="purpose" class="form-control" rows="3" placeholder="Describe how you will use the loan funds...">{{ old('purpose') }}</textarea>
                                </div>
                            </div>
                            
                            <!-- Loan Summary -->
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="mb-3">Loan Summary</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Interest Amount:</strong></p>
                                        <p class="text-primary" id="total_interest">KES 0</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Total Repayment:</strong></p>
                                        <p class="text-success" id="total_repayment">KES 0</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Weekly Payment:</strong></p>
                                        <p class="text-info" id="weekly_payment">KES 0</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Cycle Payment:</strong></p>
                                        <p class="text-warning" id="cycle_payment">KES 0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="card mb-4 wow fadeInUp" data-wow-duration="0.8s">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-file-earmark-arrow-up me-2"></i>Supporting Documents</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Loan Form(s)</label>
                                    <input type="file" name="loan_form[]" class="form-control" multiple accept=".pdf,image/*">
                                    <small class="text-muted">PDF, JPG or PNG (up to 5MB each)</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">M-Pesa Statement(s)</label>
                                    <input type="file" name="mpesa_statement[]" class="form-control" multiple accept=".pdf,image/*">
                                    <small class="text-muted">PDF, JPG or PNG (up to 5MB each)</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Business Photo(s)</label>
                                    <input type="file" name="business_photo[]" class="form-control" multiple accept="image/*">
                                    <small class="text-muted">JPG or PNG (up to 5MB each)</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Other Supporting Documents</label>
                                    <input type="file" name="documents[]" class="form-control" multiple accept=".pdf,image/*">
                                    <small class="text-muted">PDF, JPG or PNG (up to 5MB each)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mb-5 wow fadeInUp" data-wow-duration="0.8s">
                        <button type="submit" class="btn_theme btn_theme_active btn-lg">
                            Submit Application <i class="bi bi-arrow-up-right"></i><span></span>
                        </button>
                        <a href="{{ route('home') }}" class="btn_theme ms-3">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- Application Form Section End -->

<script>
const frequencyOptions = @json(collect($repaymentFrequencies)->map(fn($freq, $key) => array_merge($freq, ['key' => $key]))->values());

function applyFrequency() {
    const selectedKey = document.getElementById('repayment_frequency').value;
    const option = frequencyOptions.find(opt => opt.key === selectedKey);
    if (option) {
        document.getElementById('interest_rate').value = option.rate;
        document.getElementById('repayment_interval_weeks').value = option.weeks;
        document.getElementById('interest_rate_display').value = option.rate;
        calculateTotals();
    }
}

function calculateTotals() {
    const amount = parseFloat(document.getElementById('loan_amount').value) || 0;
    const durationMonths = Math.max(1, parseInt(document.getElementById('duration_months').value) || 1);
    const totalWeeks = Math.max(1, Math.ceil(durationMonths * 4));
    const intervalWeeks = Math.max(1, parseInt(document.getElementById('repayment_interval_weeks').value) || 4);
    const intervalCount = Math.max(1, Math.ceil(totalWeeks / intervalWeeks));
    const rate = parseFloat(document.getElementById('interest_rate').value) || 0;
    const registration = parseFloat(document.getElementById('registration_fee').value) || 0;
    
    const interest = ((rate / 100) * amount) * intervalCount;
    const totalInterest = Math.round((interest + Number.EPSILON) * 100) / 100;
    const totalRepayment = Math.round((amount + totalInterest + registration + Number.EPSILON) * 100) / 100;
    const weeklyPayment = Math.round(((totalRepayment / totalWeeks) + Number.EPSILON) * 100) / 100;
    const cyclePayment = Math.round(((totalRepayment / intervalCount) + Number.EPSILON) * 100) / 100;

    document.getElementById('total_interest').textContent = 'KES ' + totalInterest.toLocaleString();
    document.getElementById('total_repayment').textContent = 'KES ' + totalRepayment.toLocaleString();
    document.getElementById('weekly_payment').textContent = 'KES ' + weeklyPayment.toLocaleString();
    document.getElementById('cycle_payment').textContent = 'KES ' + cyclePayment.toLocaleString();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    applyFrequency();
});
</script>
@endsection
