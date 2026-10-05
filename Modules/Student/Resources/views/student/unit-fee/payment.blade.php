@extends('student::student.layouts.master')
@section('title', 'Student | Unit Fees')

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

<style>
    .valid {
        color: green;
    }

    .invalid {
        color: red;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pay {{ $fee->name }} Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.fee.unit.index') }}">Unit Fees</a></li>
                    <li class="breadcrumb-item active">Pay Fee</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form role="form" action="{{ route('student.fee.unit.pay', $fee->id) }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ $stripe_key }}" id="payment-form" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Pay {{ $fee->name }} Fee</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Fee name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Fee Name" value="{{ $fee->name }}" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fee">Fee Amount</label>
                                    <input type="text" name="fee" class="form-control" id="fee" placeholder="Enter Fee Amount" value="{{ $fee->fee }}" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="payment_type">Payment Type</label> <span class="required">*</span>
                                    <select class="form-control" name="payment_type" id="payment_type" onchange="payType(this);">
                                        <option value="" selected disabled>-- Select Payment Type --</option>
                                        @foreach($payments as $payment)
                                        <option value="{{ $payment->name }}">{{ $payment->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row" style="display: none;" id="bank-transfer">
                                <div class="form-group col-md-4">
                                    <label for="receipt">Upload Receipt</label> <span class="required">*</span>
                                    <input type="file" name="receipt" class="form-control" id="receipt" placeholder="Choose Receipt">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="remarks">Remarks</label> <span class="required">*</span>
                                    <input type="text" name="remarks" class="form-control" id="remarks" placeholder="Enter Remarks">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="paid_date">Paid Date</label> <span class="required">*</span>
                                    <input type="date" name="paid_date" class="form-control" id="paid_date">
                                </div>
                            </div>

                            <div class="row" style="display: none;" id="stripe">
                                <div class="form-group col-md-6">
                                    <label for="card_name">Name on Card</label> <span class="required">*</span>
                                    <input type="text" name="card_name" class="form-control" id="card_name" placeholder="Enter Name on Card">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="card_no">Card Number</label> <span class="required">*</span>
                                    <input autocomplete='off' id="card_no" name="card_no" class='form-control card-number' size='20' type='text' maxlength="16">
                                    <div id="validation-result"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cvc">CVC</label> <span class="required">*</span>
                                    <input autocomplete='off' id="cvc" name="cvc" class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="expiration_month">Expiration Month</label> <span class="required">*</span>
                                    <input class='form-control card-expiry-month' name="expiration_month" id="expiration_month" placeholder='MM' size='2' type='text'>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="expiration_year">Expiration Year</label> <span class="required">*</span>
                                    <input class='form-control card-expiry-year' id="expiration_year" name="expiration_year" placeholder='YYYY' size='4' type='text'>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Pay</button>
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
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script>
    $(function() {
        $('#payment-form').validate({
            rules: {
                payment_type: {
                    required: true
                },
                receipt: {
                    required: true
                },
                remarks: {
                    required: true
                },
                paid_date: {
                    required: true
                },
                card_name: {
                    required: true,
                },
                card_no: {
                    required: true,
                    digits: true,
                    maxlength: 16,
                    minlength: 13,
                },
                cvc: {
                    required: true,
                    digits: true,
                    minlength: 3,
                    maxlength: 4,
                },
                expiration_month: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                },
                expiration_year: {
                    required: true,
                    digits: true,
                    maxlength: 4,
                },
            },
            messages: {
                payment_type: "Please choose one payment type",
                receipt: "Please upload receipt",
                remarks: "Please enter remarks",
                paid_date: "Please enter paid date",
                card_name: "Please enter name on card",
                card_no: {
                    required: "Please enter card number",
                    digits: "Please enter only digits"
                },
                cvc: {
                    required: "Please enter cvc",
                    digits: "Please enter only digits"
                },
                expiration_month: {
                    required: "Please enter expiration month",
                    digits: "Please enter only digits"
                },
                expiration_year: {
                    required: "Please enter expiration year",
                    digits: "Please enter only digits"
                },

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

    function payType(that) {
        console.log(that.value);
        if (that.value == "Stripe") {
            console.log('stripe:', that.value);
            document.getElementById("bank-transfer").style.display = "none";
            document.getElementById("stripe").style.display = "flex";

            const cardNumberInput = document.getElementById('card_no');
            const validationResult = document.getElementById('validation-result');

            // Function to validate credit card number using Luhn Algorithm
            function validateCreditCard(number) {
                let nCheck = 0,
                    bEven = false;
                number = number.replace(/\D/g, '');

                for (let n = number.length - 1; n >= 0; n--) {
                    let cDigit = number.charAt(n),
                        nDigit = parseInt(cDigit, 10);

                    if (bEven) {
                        if ((nDigit *= 2) > 9) nDigit -= 9;
                    }

                    nCheck += nDigit;
                    bEven = !bEven;
                }

                return (nCheck % 10) === 0;
            }

            // Real-time validation while typing
            cardNumberInput.addEventListener('input', function() {
                const cardNumber = cardNumberInput.value;
                if (validateCreditCard(cardNumber)) {
                    validationResult.innerText = 'Card is valid!';
                    validationResult.className = 'valid';
                } else {
                    validationResult.innerText = 'Card is invalid!';
                    validationResult.className = 'invalid';
                }
            });

            document.getElementById("card_no").addEventListener("keyup", validateCreditCard);

            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function(e) {
                var $form = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'
                    ].join(', '),
                    $inputs = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        e.preventDefault();
                    }
                });

                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    /* token contains id, last4, and card type */
                    var token = response['id'];

                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }
        } else {
            console.log('bank-transfer:', that.value);
            document.getElementById("bank-transfer").style.display = "flex";
            document.getElementById("stripe").style.display = "none";
        }
    }
</script>
@endsection
