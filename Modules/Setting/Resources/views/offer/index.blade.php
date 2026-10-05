@extends('user::layouts.master')
@section('title', 'Admin | Offer Settings')

@section('content')
@if ($text = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@elseif ($text = Session::get('failure'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $text }}</strong>
</div>
@endif
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Offer Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item active">Offer Settings</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="updatesetting" action="{{ route('admin.setting.offer.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Offer Settings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_signed_by_name">Offer Signed By Name</label>
                                        <input type="text" name="offer_signed_by_name" class="form-control" id="offer_signed_by_name" placeholder="Enter Offer Signed By Name" value="{{ getSettingValue('offer_signed_by_name') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_signed_by_designation">Offer Signed By Designation</label>
                                        <input type="text" name="offer_signed_by_designation" class="form-control" id="offer_signed_by_designation" placeholder="Enter Offer Signed By Designation" value="{{ getSettingValue('offer_signed_by_designation') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_organisation_email">Organisation Email</label>
                                        <input type="text" name="offer_organisation_email" class="form-control" id="offer_organisation_email" placeholder="Enter Organisation Email" value="{{ getSettingValue('offer_organisation_email') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_organisation_phone">Organisation Phone</label>
                                        <input type="text" name="offer_organisation_phone" class="form-control" id="offer_organisation_phone" placeholder="Enter Organisation Phone" value="{{ getSettingValue('offer_organisation_phone') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_cricos_provider_code">CRICOS Provider Code</label>
                                        <input type="text" name="offer_cricos_provider_code" class="form-control" id="offer_cricos_provider_code" placeholder="Enter CRICOS Provider Code" value="{{ getSettingValue('offer_cricos_provider_code') }}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3"><i class="fas fa-file-contract mr-1"></i> Terms and Conditions of Enrolment</h5>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="offer_terms_and_conditions">Terms and Conditions</label>
                                        <textarea name="offer_terms_and_conditions" class="form-control" id="offer_terms_and_conditions" rows="10" placeholder="Enter the enrolment terms and conditions issued to students">{{ getSettingValue('offer_terms_and_conditions') }}</textarea>
                                        <small class="form-text text-muted">Printed on the final page of the offer letter. Supply your own code of conduct, fees and refunds policy, complaints and appeals process, and any disclosures your regulator requires.</small>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3"><i class="fas fa-university mr-1"></i> Bank Account Details</h5>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_bank_account_name">Bank Account Name</label>
                                        <input type="text" name="offer_bank_account_name" class="form-control" id="offer_bank_account_name" placeholder="Enter Bank Account Name" value="{{ getSettingValue('offer_bank_account_name') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_bank_name">Bank Name</label>
                                        <input type="text" name="offer_bank_name" class="form-control" id="offer_bank_name" placeholder="Enter Bank Name" value="{{ getSettingValue('offer_bank_name') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_bank_bsb">Bank BSB</label>
                                        <input type="text" name="offer_bank_bsb" class="form-control" id="offer_bank_bsb" placeholder="Enter Bank BSB" value="{{ getSettingValue('offer_bank_bsb') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_bank_account_number">Bank Account Number</label>
                                        <input type="text" name="offer_bank_account_number" class="form-control" id="offer_bank_account_number" placeholder="Enter Bank Account Number" value="{{ getSettingValue('offer_bank_account_number') }}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_signature">Offer Signature</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="offer_signature" class="custom-file-input" id="offer_signature" onchange="readURL(this);">
                                                <label class="custom-file-label" for="offer_signature">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @if (getSettingValue('offer_signature'))
                                        <img src="{{ asset(getSettingValue('offer_signature')) }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @else
                                        <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="offer_college_logo">Offer College Logo</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="offer_college_logo" class="custom-file-input" id="offer_college_logo" onchange="readCollegeURL(this);">
                                                <label class="custom-file-label" for="offer_college_logo">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @if (getSettingValue('offer_college_logo'))
                                        <img src="{{ asset(getSettingValue('offer_college_logo')) }}" id="college-box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @else
                                        <img src="{{ asset('themes/AdminLTE/dist/img/boxed-bg.png') }}" id="college-box-image" alt="" style="width: 128px; border: #ebebeb 1px solid;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#box-image')
                    .attr('src', e.target.result)
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readCollegeURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#college-box-image')
                    .attr('src', e.target.result)
                    .width(128)
                    .height(128);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection