@extends('user::layouts.master')
@section('title', 'Admin | Add Fee')

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
                <h1>Add Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a @if ($course->registered == 1) href="{{ route('admin.course.index') }}" @else href="{{ route('admin.unregistered.index') }}" @endif>Courses</a></li>
                    <li class="breadcrumb-item"><a @if ($course->registered == 1) href="{{ route('admin.course.fee.index', $course->id) }}" @else href="{{ route('admin.unregistered.fee.index', $course->id) }}" @endif>Fees</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addfee" @if ($course->registered == 1) action="{{ route('admin.course.fee.store', $course->id) }}" @else action="{{ route('admin.unregistered.fee.store', $course->id) }}" @endif method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Fee</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name">Name</label> <span class="required">*</span>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" disabled>-- Select Status --</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div id="fee-types-container">
                                @foreach($feeTypes as $index => $feeType)
                                <div class="row mb-3 fee-type-row">
                                    <div class="col-md-3">
                                        <select class="form-control fee-type-select" data-index="{{ $index }}">
                                            @foreach($feeTypes as $option)
                                            <option value="{{ $option->amount }}" data-key="{{ $option->key }}" {{ $option->name == $feeType->name ? 'selected' : '' }}>
                                                {{ $option->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <!-- Set initial name in fees array -->
                                        <input type="text" name="fees[{{ $feeType->key }}]" value="{{ $feeType->amount }}" class="form-control amount-input" placeholder="Amount" data-index="{{ $index }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-fee-type">Delete</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <button type="button" id="add-fee-type" class="btn btn-success">Add Fee Type</button>
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
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#addfee').validate({
            rules: {
                name: {
                    required: true,
                },
                "fees[application_fee]": {
                    required: true,
                },
                "fees[admission_fee]": {
                    required: true,
                },
                "fees[security_fee]": {
                    required: true,
                },
                "fees[semester_fee]": {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter unit name",
                "fees[application_fee]": {
                    required: "Please enter application fee",
                },
                "fees[admission_fee]": {
                    required: "Please enter admission fee",
                },
                "fees[security_fee]": {
                    required: "Please enter security deposit fee",
                },
                "fees[semester_fee]": {
                    required: "Please enter semester fee",
                },
                status: "Please select one status",
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

    document.addEventListener('DOMContentLoaded', function() {
        let container = document.getElementById('fee-types-container');
        let addButton = document.getElementById('add-fee-type');
        let form = document.getElementById('fee-types-form');

        addButton.addEventListener('click', function() {
            let newIndex = document.querySelectorAll('.fee-type-row').length; // Get current count of rows
            let newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-3', 'fee-type-row');

            newRow.innerHTML = `
            <div class="col-md-3">
                <select class="form-control fee-type-select" data-index="${newIndex}">
                    @foreach($feeTypes as $option)
                        <option value="{{ $option->amount }}" data-key="{{ $option->key }}">{{ $option->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <!-- Set initial name in fees array -->
                <input type="text" name="fees[{{ $first_fee->key }}]" class="form-control amount-input" placeholder="Amount" data-index="${newIndex}" value={{ $first_fee->amount }}>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-fee-type">Delete</button>
            </div>
        `;

            container.appendChild(newRow);
            updateEventListeners(); // Update event listeners for dynamically added elements
        });

        container.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-fee-type')) {
                event.target.closest('.fee-type-row').remove();
            }
        });

        function updateEventListeners() {
            document.querySelectorAll('.fee-type-select').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    let selectedOption = selectElement.options[selectElement.selectedIndex];
                    let key = selectedOption.getAttribute('data-key');
                    let index = selectElement.getAttribute('data-index');
                    let amountInput = document.querySelector(`input[data-index='${index}']`);

                    // Update the name attribute dynamically with the selected key within the fees array
                    amountInput.name = `fees[${key}]`;
                    console.log(`Updated name for input field to: fees[${key}]`);
                });
            });
        }

        function validateFeeTypes() {
            let selectedKeys = [];
            let duplicates = false;

            document.querySelectorAll('.fee-type-select').forEach(function(selectElement) {
                let selectedOption = selectElement.options[selectElement.selectedIndex];
                let key = selectedOption.getAttribute('data-key');

                if (selectedKeys.includes(key)) {
                    duplicates = true;
                } else {
                    selectedKeys.push(key);
                }
            });

            return duplicates;
        }

        form.addEventListener('submit', function(event) {
            if (validateFeeTypes()) {
                event.preventDefault(); // Prevent form submission
                alert('Duplicate fee types detected. Please select different fee types.');
            }
        });

        updateEventListeners(); // Initial call to set up event listeners
    });
</script>
@endsection