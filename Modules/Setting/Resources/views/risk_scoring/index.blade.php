@extends('user::layouts.master')
@section('title', 'Admin | Risk Scoring Settings')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $text }}</strong>
</div>
@endif
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Risk Scoring Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Risk Scoring Engine</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <form id="riskScoringForm" action="{{ route('admin.setting.risk-scoring.update') }}" method="POST">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Risk Scoring Engine Weights</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Define the weight for each risk signal. The total sum of all weights must equal exactly 100%.
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="attendance_weight">Attendance Weight (%)</label>
                                    <input type="number" name="attendance_weight" id="attendance_weight" class="form-control risk-weight" min="0" max="100" value="{{ old('attendance_weight', $settings->attendance_weight) }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="assignment_weight">Assignment Weight (%)</label>
                                    <input type="number" name="assignment_weight" id="assignment_weight" class="form-control risk-weight" min="0" max="100" value="{{ old('assignment_weight', $settings->assignment_weight) }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="grade_weight">Grade Weight (%)</label>
                                    <input type="number" name="grade_weight" id="grade_weight" class="form-control risk-weight" min="0" max="100" value="{{ old('grade_weight', $settings->grade_weight) }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fee_weight">Fee Payment Weight (%)</label>
                                    <input type="number" name="fee_weight" id="fee_weight" class="form-control risk-weight" min="0" max="100" value="{{ old('fee_weight', $settings->fee_weight) }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="engagement_weight">Engagement Weight (%)</label>
                                    <input type="number" name="engagement_weight" id="engagement_weight" class="form-control risk-weight" min="0" max="100" value="{{ old('engagement_weight', $settings->engagement_weight) }}" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Total Sum: <span id="total_sum" class="badge badge-success">100%</span></h5>
                                    <p id="sum_error" class="text-danger" style="display: none;">The sum of all weights must equal 100%.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Save Settings</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const weightInputs = document.querySelectorAll('.risk-weight');
        const totalSumDisplay = document.getElementById('total_sum');
        const sumError = document.getElementById('sum_error');
        const submitBtn = document.getElementById('submitBtn');

        function calculateSum() {
            let sum = 0;
            weightInputs.forEach(input => {
                sum += parseInt(input.value) || 0;
            });
            return sum;
        }

        function updateUI() {
            const sum = calculateSum();
            totalSumDisplay.textContent = sum + '%';

            if (sum === 100) {
                totalSumDisplay.className = 'badge badge-success';
                sumError.style.display = 'none';
                submitBtn.disabled = false;
            } else {
                totalSumDisplay.className = 'badge badge-danger';
                sumError.style.display = 'block';
                submitBtn.disabled = true;
            }
        }

        weightInputs.forEach(input => {
            input.addEventListener('input', updateUI);
        });

        updateUI();
    });
</script>
@endsection
