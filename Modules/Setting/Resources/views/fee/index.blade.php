@extends('user::layouts.master')
@section('title', 'Admin | Fee Settings')

@section('content')
<style>
    .settings-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .settings-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fff;
    }
    .settings-card-header .section-icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; flex-shrink: 0;
    }
    .settings-card-header .section-icon.purple { background: #ede7f6; color: #7b1fa2; }
    .settings-card-header .section-icon.teal   { background: #e0f2f1; color: #00796b; }
    .settings-card-header h5 {
        margin: 0 0 2px;
        font-size: 14px; font-weight: 700; color: #343a40;
    }
    .settings-card-header p {
        margin: 0;
        font-size: 12px; color: #6c757d;
    }

    .setting-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        border-bottom: 1px solid #f8f9fa;
        background: #fff;
        transition: background .15s;
        gap: 16px;
    }
    .setting-row:last-child { border-bottom: none; }
    .setting-row:hover { background: #fafbfc; }
    .setting-row.disabled-row { opacity: .5; pointer-events: none; }

    .setting-info { flex: 1; min-width: 0; }
    .setting-info .setting-name {
        font-size: 14px; font-weight: 600; color: #343a40;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 3px;
    }
    .setting-info .setting-desc {
        font-size: 12px; color: #6c757d; line-height: 1.5;
    }
    .setting-status-tag {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .5px; padding: 2px 8px; border-radius: 20px;
    }
    .setting-status-tag.on  { background: #d4edda; color: #155724; }
    .setting-status-tag.off { background: #f8d7da; color: #721c24; }

    /* Toggle switch */
    .toggle-wrap { flex-shrink: 0; }
    .toggle-switch {
        position: relative;
        width: 48px; height: 26px;
        cursor: pointer;
        display: inline-block;
    }
    .toggle-switch input { display: none; }
    .toggle-track {
        position: absolute; inset: 0;
        background: #ced4da;
        border-radius: 26px;
        transition: background .2s;
    }
    .toggle-track::after {
        content: '';
        position: absolute;
        top: 3px; left: 3px;
        width: 20px; height: 20px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,.25);
        transition: transform .2s;
    }
    .toggle-switch input:checked + .toggle-track { background: #28a745; }
    .toggle-switch input:checked + .toggle-track::after { transform: translateX(22px); }

    .save-bar {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
    .save-bar p { margin: 0; font-size: 13px; color: #6c757d; }
    .btn-save-settings {
        font-size: 14px; font-weight: 600;
        padding: 9px 28px;
        border-radius: 7px;
        letter-spacing: .3px;
    }
</style>

@if ($text = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i><strong>{{ $text }}</strong>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle mr-2"></i><strong>{{ $text }}</strong>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<section class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:22px;font-weight:700;">Fee Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Fee Settings</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <form id="updatesetting" action="{{ route('admin.setting.fee.update') }}" method="POST">
        @csrf
        <div class="container-fluid">

            {{-- Core Modules --}}
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="section-icon purple"><i class="fas fa-cubes"></i></div>
                    <div>
                        <h5>Core Modules</h5>
                        <p>Enable or disable the primary fee and payment features across the platform.</p>
                    </div>
                </div>

                {{-- Fee Module --}}
                @php $feeOn = feeSetting('fee_module') == 'yes'; @endphp
                <div class="setting-row">
                    <div class="setting-info">
                        <div class="setting-name">
                            <i class="fas fa-file-invoice-dollar" style="color:#7b1fa2;font-size:13px;"></i>
                            Fee Module
                            <span class="setting-status-tag {{ $feeOn ? 'on' : 'off' }}" id="tag-fee_module">{{ $feeOn ? 'Enabled' : 'Disabled' }}</span>
                        </div>
                        <div class="setting-desc">Master switch for the entire fee management system. Disabling this hides fee-related menus and functionality for all users.</div>
                    </div>
                    <div class="toggle-wrap">
                        <label class="toggle-switch">
                            <input type="checkbox" id="toggle-fee_module" {{ $feeOn ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                        </label>
                        <input type="hidden" name="fee_module" id="fee_module" value="{{ feeSetting('fee_module') }}">
                    </div>
                </div>

                {{-- Payment Module --}}
                @php $payOn = feeSetting('payment_module') == 'yes'; @endphp
                <div class="setting-row">
                    <div class="setting-info">
                        <div class="setting-name">
                            <i class="fas fa-credit-card" style="color:#7b1fa2;font-size:13px;"></i>
                            Payment Module
                            <span class="setting-status-tag {{ $payOn ? 'on' : 'off' }}" id="tag-payment_module">{{ $payOn ? 'Enabled' : 'Disabled' }}</span>
                        </div>
                        <div class="setting-desc">Enables recording, tracking, and reporting of installment payments against student fees.</div>
                    </div>
                    <div class="toggle-wrap">
                        <label class="toggle-switch">
                            <input type="checkbox" id="toggle-payment_module" {{ $payOn ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                        </label>
                        <input type="hidden" name="payment_module" id="payment_module" value="{{ feeSetting('payment_module') }}">
                    </div>
                </div>

                {{-- Unit Wise Fee --}}
                @php $unitOn = feeSetting('unit_wise_fee') == 'yes'; @endphp
                <div class="setting-row">
                    <div class="setting-info">
                        <div class="setting-name">
                            <i class="fas fa-layer-group" style="color:#7b1fa2;font-size:13px;"></i>
                            Unit Wise Fee
                            <span class="setting-status-tag {{ $unitOn ? 'on' : 'off' }}" id="tag-unit_wise_fee">{{ $unitOn ? 'Enabled' : 'Disabled' }}</span>
                        </div>
                        <div class="setting-desc">When enabled, fees are assigned and tracked at the individual unit level rather than at the course level.</div>
                    </div>
                    <div class="toggle-wrap">
                        <label class="toggle-switch">
                            <input type="checkbox" id="toggle-unit_wise_fee" {{ $unitOn ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                        </label>
                        <input type="hidden" name="unit_wise_fee" id="unit_wise_fee" value="{{ feeSetting('unit_wise_fee') }}">
                    </div>
                </div>
            </div>

            {{-- Scholarship Settings --}}
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="section-icon teal"><i class="fas fa-graduation-cap"></i></div>
                    <div>
                        <h5>Scholarship Settings</h5>
                        <p>Control scholarship application availability and the approval workflow.</p>
                    </div>
                </div>

                {{-- Scholarship Module --}}
                @php $schOn = feeSetting('scholarship_module') == 'yes'; @endphp
                <div class="setting-row">
                    <div class="setting-info">
                        <div class="setting-name">
                            <i class="fas fa-award" style="color:#00796b;font-size:13px;"></i>
                            Scholarship Module
                            <span class="setting-status-tag {{ $schOn ? 'on' : 'off' }}" id="tag-scholarship_module">{{ $schOn ? 'Enabled' : 'Disabled' }}</span>
                        </div>
                        <div class="setting-desc">Enables scholarship programs and allows admins to apply fee discounts to student fees. Disabling this hides all scholarship-related options.</div>
                    </div>
                    <div class="toggle-wrap">
                        <label class="toggle-switch">
                            <input type="checkbox" id="toggle-scholarship_module" {{ $schOn ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                        </label>
                        <input type="hidden" name="scholarship_module" id="scholarship_module" value="{{ feeSetting('scholarship_module') }}">
                    </div>
                </div>

                {{-- Require Approval --}}
                @php $approvalOn = feeSetting('scholarship_require_approval') == 'yes'; @endphp
                <div class="setting-row {{ !$schOn ? 'disabled-row' : '' }}" id="row-scholarship_require_approval">
                    <div class="setting-info">
                        <div class="setting-name">
                            <i class="fas fa-user-check" style="color:#00796b;font-size:13px;"></i>
                            Require Scholarship Approval
                            <span class="setting-status-tag {{ $approvalOn ? 'on' : 'off' }}" id="tag-scholarship_require_approval">{{ $approvalOn ? 'Enabled' : 'Disabled' }}</span>
                        </div>
                        <div class="setting-desc">When enabled, scholarship applications must be reviewed and approved by an admin before the discount is applied to the student's fee. When disabled, discounts are applied immediately on submission.</div>
                    </div>
                    <div class="toggle-wrap">
                        <label class="toggle-switch">
                            <input type="checkbox" id="toggle-scholarship_require_approval" {{ $approvalOn ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                        </label>
                        <input type="hidden" name="scholarship_require_approval" id="scholarship_require_approval" value="{{ feeSetting('scholarship_require_approval') }}">
                    </div>
                </div>

                {{-- Max Scholarships Per Fee --}}
                @php
                    $maxPerFeeVal = feeSetting('scholarship_max_per_fee');
                    $maxPerFeeVal = is_numeric($maxPerFeeVal) ? max(1, (int) $maxPerFeeVal) : 2;
                @endphp
                <div class="setting-row {{ !$schOn ? 'disabled-row' : '' }}" id="row-scholarship_max_per_fee">
                    <div class="setting-info">
                        <div class="setting-name">
                            <i class="fas fa-layer-group" style="color:#00796b;font-size:13px;"></i>
                            Max Scholarships Per Fee
                        </div>
                        <div class="setting-desc">The maximum number of approved scholarships that can stack on a single student fee. Prevents accidental over-discounting.</div>
                    </div>
                    <div class="toggle-wrap">
                        <input type="number" name="scholarship_max_per_fee" id="scholarship_max_per_fee" class="form-control text-center"
                            value="{{ $maxPerFeeVal }}" min="1" max="10" step="1" style="width:80px;">
                    </div>
                </div>
            </div>

            {{-- Save bar --}}
            <div class="save-bar">
                <p><i class="fas fa-info-circle mr-1"></i>Changes take effect immediately after saving.</p>
                <button type="submit" class="btn btn-primary btn-save-settings">
                    <i class="fas fa-save mr-2"></i>Save Settings
                </button>
            </div>

        </div>
    </form>
</section>
@endsection

@section('scripts')
<script>
    // Sync each toggle checkbox → hidden input + status tag
    var toggles = [
        'fee_module',
        'payment_module',
        'unit_wise_fee',
        'scholarship_module',
        'scholarship_require_approval'
    ];

    toggles.forEach(function (key) {
        var checkbox = document.getElementById('toggle-' + key);
        var hidden   = document.getElementById(key);
        var tag      = document.getElementById('tag-' + key);

        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            var isOn = this.checked;
            hidden.value = isOn ? 'yes' : 'no';
            tag.textContent = isOn ? 'Enabled' : 'Disabled';
            tag.className = 'setting-status-tag ' + (isOn ? 'on' : 'off');

            // When scholarship module is toggled, enable/disable its dependent rows
            if (key === 'scholarship_module') {
                ['row-scholarship_require_approval', 'row-scholarship_max_per_fee'].forEach(function (rowId) {
                    var row = document.getElementById(rowId);
                    if (row) row.classList.toggle('disabled-row', !isOn);
                });
            }
        });
    });
</script>
@endsection
