@extends('user::layouts.register')
@section('title', 'Setup')

@section('content')

{{-- ── Left branding panel ────────────────────────── --}}
<div class="reg-left">
    <div class="reg-logo">
        <div class="reg-logo-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <span class="reg-logo-text">Eloom LMS</span>
    </div>

    <h2 class="reg-brand-headline">Welcome to your<br>Learning Platform</h2>
    <p class="reg-brand-sub">Complete the setup to get your organisation's learning management system up and running in minutes.</p>

    <ul class="reg-features">
        <li>
            <div class="feat-icon"><i class="fas fa-users"></i></div>
            <div class="feat-text">
                <strong>Manage Teams</strong>
                <span>Organise learners, trainers and administrators</span>
            </div>
        </li>
        <li>
            <div class="feat-icon"><i class="fas fa-book-open"></i></div>
            <div class="feat-text">
                <strong>Deliver Courses</strong>
                <span>Create and assign learning content at scale</span>
            </div>
        </li>
        <li>
            <div class="feat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="feat-text">
                <strong>Track Progress</strong>
                <span>Real-time reporting and compliance dashboards</span>
            </div>
        </li>
    </ul>
</div>

{{-- ── Right form panel ────────────────────────────── --}}
<div class="reg-right">
    <div class="reg-right-inner">

        {{-- Header --}}
        <div class="reg-header">
            <h1>Initial Setup</h1>
            <p>Create your administrator account and organisation to get started.</p>
        </div>

        {{-- Session error --}}
        @if(session('error'))
        <div class="reg-alert error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        {{-- Stepper --}}
        <div class="reg-stepper" id="reg-stepper">
            <div class="step-item active" data-step="1">
                <div class="step-bubble"><span class="step-number">1</span></div>
                <span class="step-label">Admin Account</span>
            </div>
            <div class="step-connector" id="connector-1"></div>
            <div class="step-item" data-step="2">
                <div class="step-bubble"><span class="step-number">2</span></div>
                <span class="step-label">Company Details</span>
            </div>
            <div class="step-connector" id="connector-2"></div>
            <div class="step-item" data-step="3">
                <div class="step-bubble"><span class="step-number">3</span></div>
                <span class="step-label">Delivery Site</span>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('register') }}" method="post" enctype="multipart/form-data" id="reg-form" novalidate>
            @csrf

            {{-- ── Step 1: Admin Account ──────────────────── --}}
            <div class="reg-step active" id="step-1">
                <div class="reg-section-title">
                    <div class="section-icon"><i class="fas fa-user"></i></div>
                    Administrator Details
                </div>

                <div class="reg-row">
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="first_name">First Name <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-user reg-input-icon"></i>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name" placeholder="Enter first name"
                                    value="{{ old('first_name') }}" required>
                            </div>
                            @error('first_name')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="family_name">Family Name <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-user reg-input-icon"></i>
                                <input type="text" class="form-control @error('family_name') is-invalid @enderror"
                                    id="family_name" name="family_name" placeholder="Enter family name"
                                    value="{{ old('family_name') }}" required>
                            </div>
                            @error('family_name')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="email">Email Address <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-envelope reg-input-icon"></i>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="admin@example.com"
                                    value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="password">Password <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-lock reg-input-icon"></i>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Create a strong password" required>
                            </div>
                            @error('password')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="phone">Phone Number <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-phone reg-input-icon"></i>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" placeholder="+61 000 000 000"
                                    value="{{ old('phone') }}" required>
                            </div>
                            @error('phone')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="image">Profile Photo <span class="required">*</span></label>
                            <label class="reg-file-label" for="image">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span id="image-label">Click to upload photo</span>
                            </label>
                            <input type="file" class="reg-file-input @error('image') is-invalid @enderror"
                                id="image" name="image" accept="image/*" required>
                            @error('image')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="reg-nav">
                    <span></span>
                    <button type="button" class="reg-btn reg-btn-primary" onclick="nextStep(1)">
                        Continue <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ── Step 2: Company Details ─────────────────── --}}
            <div class="reg-step" id="step-2">
                <div class="reg-section-title">
                    <div class="section-icon"><i class="fas fa-building"></i></div>
                    Company Details
                </div>

                <div class="reg-row">
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="company_name">Company Name <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-building reg-input-icon"></i>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                    id="company_name" name="company_name" placeholder="Acme Corporation"
                                    value="{{ old('company_name') }}" required>
                            </div>
                            @error('company_name')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="company_ceo">C.E.O. <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-user-tie reg-input-icon"></i>
                                <input type="text" class="form-control @error('company_ceo') is-invalid @enderror"
                                    id="company_ceo" name="company_ceo" placeholder="Chief Executive Officer name"
                                    value="{{ old('company_ceo') }}" required>
                            </div>
                            @error('company_ceo')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="company_email">Company Email <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-envelope reg-input-icon"></i>
                                <input type="email" class="form-control @error('company_email') is-invalid @enderror"
                                    id="company_email" name="company_email" placeholder="info@company.com"
                                    value="{{ old('company_email') }}" required>
                            </div>
                            @error('company_email')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="company_phone">Company Phone <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-phone-alt reg-input-icon"></i>
                                <input type="text" class="form-control @error('company_phone') is-invalid @enderror"
                                    id="company_phone" name="company_phone" placeholder="+61 000 000 000"
                                    value="{{ old('company_phone') }}" required>
                            </div>
                            @error('company_phone')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="logo">Company Logo</label>
                            <label class="reg-file-label" for="logo">
                                <i class="fas fa-image"></i>
                                <span id="logo-label">Click to upload logo</span>
                            </label>
                            <input type="file" class="reg-file-input" id="logo" name="logo" accept="image/*">
                        </div>
                    </div>
                </div>

                {{-- Company address --}}
                <div class="reg-section-title" style="margin-top: 8px;">
                    <div class="section-icon"><i class="fas fa-map-marker-alt"></i></div>
                    Company Address
                </div>
                <div class="row">
                    @include('partials.address-fields', ['address' => null, 'addressPrefix' => 'company_', 'addressIdPrefix' => 'company'])
                </div>

                <div class="reg-nav">
                    <button type="button" class="reg-btn reg-btn-secondary" onclick="prevStep(2)">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="button" class="reg-btn reg-btn-primary" onclick="nextStep(2)">
                        Continue <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- ── Step 3: Delivery Site ───────────────────── --}}
            <div class="reg-step" id="step-3">
                <div class="reg-section-title">
                    <div class="section-icon"><i class="fas fa-map-pin"></i></div>
                    Company Delivery Site
                </div>

                <div class="reg-row">
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="site_name">Site Name <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-map-pin reg-input-icon"></i>
                                <input type="text" class="form-control @error('site_name') is-invalid @enderror"
                                    id="site_name" name="site_name" placeholder="Main Campus"
                                    value="{{ old('site_name') }}" required>
                            </div>
                            @error('site_name')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="reg-col-6">
                        <div class="reg-form-group">
                            <label class="reg-label" for="site_phone">Site Phone <span class="required">*</span></label>
                            <div class="reg-input-wrap">
                                <i class="fas fa-phone reg-input-icon"></i>
                                <input type="text" class="form-control @error('site_phone') is-invalid @enderror"
                                    id="site_phone" name="site_phone" placeholder="+61 000 000 000"
                                    value="{{ old('site_phone') }}" required>
                            </div>
                            @error('site_phone')
                                <div class="reg-error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Delivery site address --}}
                <div class="reg-section-title" style="margin-top: 8px;">
                    <div class="section-icon"><i class="fas fa-map-marker-alt"></i></div>
                    Site Address
                </div>
                <div class="row">
                    @include('partials.address-fields', ['address' => null, 'addressPrefix' => 'company_delivery_site_', 'addressIdPrefix' => 'company_delivery_site'])
                </div>

                <div class="reg-nav">
                    <button type="button" class="reg-btn reg-btn-secondary" onclick="prevStep(3)">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button type="submit" class="reg-btn reg-btn-success">
                        <i class="fas fa-check"></i> Complete Setup
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    var currentStep = 1;

    function showStep(n) {
        document.querySelectorAll('.reg-step').forEach(function(s) { s.classList.remove('active'); });
        document.getElementById('step-' + n).classList.add('active');

        document.querySelectorAll('.step-item').forEach(function(item) {
            var s = parseInt(item.dataset.step);
            item.classList.remove('active', 'completed');
            if (s === n) item.classList.add('active');
            if (s < n) item.classList.add('completed');
        });

        ['connector-1', 'connector-2'].forEach(function(id, idx) {
            var el = document.getElementById(id);
            if (el) el.classList.toggle('done', n > idx + 1);
        });

        currentStep = n;
        window.scrollTo(0, 0);
    }

    function nextStep(from) {
        var step = document.getElementById('step-' + from);
        var inputs = step.querySelectorAll('input[required], select[required], textarea[required]');
        for (var i = 0; i < inputs.length; i++) {
            if (!inputs[i].reportValidity()) return;
        }
        showStep(from + 1);
    }

    function prevStep(from) {
        showStep(from - 1);
    }

    // File input label updates
    function bindFileLabel(inputId, labelId) {
        var input = document.getElementById(inputId);
        if (!input) return;
        input.addEventListener('change', function() {
            var el = document.getElementById(labelId);
            if (el) el.textContent = this.files.length ? this.files[0].name : (inputId === 'image' ? 'Click to upload photo' : 'Click to upload logo');
        });
    }
    bindFileLabel('image', 'image-label');
    bindFileLabel('logo', 'logo-label');

    // If server returned errors, stay on step 1 (already default)
    // If any step-2/3 field has an error class, jump to that step
    (function() {
        var s3fields = document.querySelectorAll('#step-3 .is-invalid');
        var s2fields = document.querySelectorAll('#step-2 .is-invalid');
        if (s3fields.length) { showStep(3); return; }
        if (s2fields.length) { showStep(2); return; }
    })();
</script>

{{-- Nepal AJAX cascade scripts (preserved) --}}
<script>
    // Company address cascades are now handled by address-fields partial (DOMContentLoaded script).
    // The legacy inline cascades below are kept for backward-compatibility with older address partial usage.
</script>
@endsection
