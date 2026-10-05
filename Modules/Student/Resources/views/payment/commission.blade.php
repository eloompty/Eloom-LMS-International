@extends('user::layouts.master')
@section('title', 'Admin | Pay Commission')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pay Commission</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.index') }}">Students</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.student.payment.index', $payment->student_id) }}">Payments</a></li>
                    <li class="breadcrumb-item active">Pay Commission</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="paycommission" action="{{ route('admin.student.payment.commission.paid', $payment->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Commission of {{ userName('Student', $payment->student_id) }}'s Payment</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="commisssion_amount">Commission Amount</label> <span class="required">*</span>
                                <input type="text" name="commisssion_amount" class="form-control" id="commisssion_amount" value="{{ $payment->agent_commission_amount }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="paid_date">Paid Date</label> <span class="required">*</span>
                                <input type="date" name="paid_date" class="form-control" id="paid_date" placeholder="Enter Paid Date" value="{{ $today }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="receipt">Upload Receipt</label>
                                <input type="file" name="receipt" class="form-control" id="receipt" placeholder="Choose Receipt">
                            </div>
                            @if ($payment->student->studentAgent != NULL) 
                            <input type="hidden" name="agent_id"  value="{{ $payment->student->studentAgent->agent_id }}">
                            @endif
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#paycommission').validate({
            rules: {
                paid_date: {
                    required: true
                },
            },
            messages: {
                paid_date: "Please enter paid date",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

</script>
@endsection