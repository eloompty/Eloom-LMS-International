<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;

        }

        .logo img {
            height: 100px;
            width: 100px;
            object-fit: contain;
        }

        .sincerely img {
            width: 60px;
            height: auto;
            object-fit: contain;
            margin: 10px 0;
        }

        .course_completion {
            width: 550px;
            margin: 10px auto;
            text-align: center;

        }

        .course_completion .date {
            text-align: right;
            font-weight: 500;
            font-size: 12px;
            margin: 10px 0;
        }

        h4.title {
            margin: 10px 0;
            font-size: 15px;
            text-align: center;
        }

        .des {
            font-size: 13px;
            text-align: left;
            margin: 10px 0;
        }

        table {
            margin: 5px 0;
            text-align: left;
        }

        table tr th,
        table tr td {
            padding: 3px 20px 3px 0;
            font-size: 13px;
            text-align: left;
        }

        .sincerely {
            text-align: left;
            margin: 20px 0;

        }

        .sincerely h5 {
            margin: 5px 0;
            font-size: 13px;
        }

        .sincerely .regards {
            font-size: 13px;
            margin: 6px 0 0;
        }

        .sincerely h6 {
            margin: 5px 0;
            font-size: 13px;
        }

        .sincerely small {
            font-size: 12px;
        }

        .at_bottom {
            width: 382px;
            text-align: center;
            margin: 10px auto;
        }

        .at_bottom .bottom_detail {
            font-size: 10px;
            margin: 5px 0;
        }

        .at_bottom a {
            font-size: 10px;
            color: blue;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .table_border table,
        .table_border table td,
        .table_border table th {
            border: 1px solid #dddddd;
            border-collapse: collapse;
            text-align: left;
            padding: 3px 5px;
            /* text-align: center; */
            font-size: 13px;
        }

        .table_border table tfoot td {
            border: none;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;

        }

        .table_border table tbody td {
            text-align: left;
        }

        .account_detail {
            text-align: left;
            margin: 10px 0 0;
        }

        .table_border {
            margin: 20px 0;
        }

        .account_detail h4 {
            font-size: 13px;
        }

        .table_border table {
            width: 100%;
        }

        .account_detail .regards {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="course_completion">
        <div class="header">

            <div class="logo">
                <img src="{{ asset($company->logo) }}" alt="">
            </div>
        </div>
        <div class="mainpart">

            <h4 class="title">RECEIPT</h4>
            <div class="date">RECEIPT NO.: {{ $installment->id }} </div>
            <div class="date">Date: {{ dateFormat($installment->studentIntakeCourseFeePayment->paid_date) }} </div>

            <table>

                <tbody>
                    <tr>
                        <th>Student Name:</th>
                        <td>{{ userName('Student', $student->id) }}</td>
                    </tr>
                    <tr>
                        <th>Student ID:</th>
                        <td>{{ $student->id_no }}</td>
                    </tr>
                    <tr>
                        <th>Intake:</th>
                        <td> <strong>{{ $installment->studentIntakeCourseFee->intakeCourse->intake->name }}</strong> </td>
                    </tr>
                    <tr>
                        <th>Installment:</th>
                        <td> <strong>{{ $installment->name }}</strong> </td>
                    </tr>


                </tbody>
            </table>

            <div class="table_border">

                <table>
                    <tbody>
                        <tr>
                            <th>Total Amount</th>
                            <td>{{ $installment->studentIntakeCourseFeePayment->total_amount }}</td>
                        </tr>
                        @if ($installment->enrollment_fee > 0)
                        <tr>
                            <th>Enrollment Fee</th>
                            <td>{{ $installment->enrollment_fee }}</td>
                        </tr>
                        @endif
                        @if ($installment->material_fee > 0)
                        <tr>
                            <th>Material Fee</th>
                            <td>{{ $installment->material_fee }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Paid Amount</th>
                            <td>{{ $installment->studentIntakeCourseFeePayment->paid_amount }}</td>
                        </tr>
                        <tr>
                            <th>Received Date</th>
                            <td>{{ dateFormat($installment->studentIntakeCourseFeePayment->received_date) }}</td>
                        </tr>
                        <tr>
                            <th>Actual Paid Date</th>
                            <td>{{ dateFormat($installment->studentIntakeCourseFeePayment->paid_date) }}</td>
                        </tr>
                        <tr>
                            <th>Remaining Amount</th>
                            <td>{{ $installment->studentIntakeCourseFeePayment->remaining_amount }}</td>
                        </tr>
                        <tr>
                            <th>Taxable Amount</th>
                            <td>{{ $installment->studentIntakeCourseFeePayment->taxable_amount }}</td>
                        </tr>
                        <tr>
                            <th>Payment Type</th>
                            <td>{{ $installment->studentIntakeCourseFeePayment->payment_type }}</td>
                        </tr>
                        <tr>
                            <th>Agent Commission</th>
                            <td>{{ $installment->studentIntakeCourseFeePayment->agent_commission_amount }}</td>
                        </tr>
                        <tr>
                            <th>Paid to Agent</th>
                            <td>@if ($installment->studentIntakeCourseFeePayment->paid_to_agent == 1 ) Paid @else Remaining @endif</td>
                        </tr>
                        <tr>
                            <th>GST (10%)</th>
                            <td>{{ $installment->studentIntakeCourseFeePayment->gst }}</td>
                        </tr>
                        <tr>
                            <th>GST Waiver</th>
                            <td>@if ($installment->studentIntakeCourseFeePayment->gst_waiver == 1) True @else False @endif </td>
                        </tr>
                        <tr>
                            <th>Receipt</th>
                            <td><img src="{{ asset($installment->studentIntakeCourseFeePayment->receipt) }}" alt="" width="200" /></td>
                        </tr>
                        <tr>
                            <th>Notes</th>
                            <td>
                                @if ($installment->studentIntakeCourseFeePayment->paymentnotes->first())
                                {{ $installment->studentIntakeCourseFeePayment->paymentnotes->first()->notes }}
                                @endif
                            </td>
                        </tr>
                    <tfoot>


                    </tfoot>
                </table>
            </div>

        </div>
        <div class="footer">

            <div class="at_bottom">
                <div class="bottom_detail">
                    {{ $company->company_name }}, RTO Code {{ $company->rto_no }} CRICOS Provider Code {{ $company->rto_no }}<br>
                    Address : {{ fullAddress('Company', $company->id) }}<br>
                    Email: {{ $company->email }}<br><br><br><br>
                </div>
            </div>
        </div>
    </div>
</body>

</html>