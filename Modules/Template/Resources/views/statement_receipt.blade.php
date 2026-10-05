<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement of Receipt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;

        }

        .logo img{
            max-height: 100px;
            min-height: 20px;
            max-width: 150px;
            min-width: 50px;
            height: auto;
            width: auto;
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
            text-align: center;
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
                <img src="{{ $logo }}" alt="">
            </div>
        </div>
        <div class="mainpart">

            <h4 class="title">STATEMENT OF RECEIPT</h4>
            <div class="date">RECEIPT NO.: {{ $receipt_no }} </div>
            <div class="date">Date: {{ $date }} </div>

            <table>

                <tbody>
                    <tr>
                        <th>Student Name:</th>
                        <td>{{ $student_name }}</td>
                    </tr>
                    <tr>
                        <th>Student ID:</th>
                        <td>{{ $student_id }}</td>
                    </tr>
                    <tr>
                        <th>Intake:</th>
                        <td> <strong>{{ $intake }}</strong> </td>
                    </tr>


                </tbody>
            </table>

            <div class="table_border">

                <table>
                    <thead>
                        <tr>
                            <th>DESCRIPTION</th>
                            <th>TUITION AMOUNT (AUD)</th>
                            <th>PAYMENT (AUD)</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tuition Payment - {{ $fee_course_id }}</td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>{{ $fee_course_name }}</td>
                            <td>${{ $total_fee }}</td>
                            <td></td>
                        </tr>
                        
                        @foreach ($installment_payments as $payment)
                        <tr>
                            <td>{{ $payment['payment_name'] }}</td>
                            <td>${{ $payment['total_amount'] }}</td>
                            <td>${{ $payment['paid_amount'] }}</td>
                        </tr>
                        @endforeach

                        <tr>
                            <td style="background-color: #cccccc63;text-align: right;">Total tuition fee received</td>
                            <td style="background-color: #cccccc63;"></td>
                            <td>${{ $total_received }}</td>
                        </tr>

                    </tbody>
                    <tfoot>


                    </tfoot>
                </table>
            </div>
            <table style="width: 100%;">
                <tr>
                    <td style="border: none;">

                    </td>
                    <td style="border: none; text-align: right;">
                        <strong> Balance (AUD) </strong>
                    </td>
                    <td style="border-top: 1px solid #dddddd; border-bottom: 1px solid #dddddd;width: 27%; text-align: center;">
                        <strong> ${{ $balance }}</strong>
                    </td>
                </tr>
            </table>
            <div class="account_detail">
                <h4>Account Details:</h4>
                <div class="regards">
                    Account Name: {{ $account_name }}</div>
                <div class="regards">
                    BSB: {{ $bsb }}</div>
                <div class="regards">
                    Account Number: {{ $account_number }} </div>
                <div class="regards">
                    Bank Name: {{ $bank_name }}</div>
            </div>
        </div>
        <div class="footer">

            <div class="at_bottom">
                <div class="bottom_detail"><?php echo $footer; ?></div>
            </div>
        </div>
    </div>
</body>

</html>