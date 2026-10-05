<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt for Unit Fee Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
            position: relative;
        }
        .header img {
            position: absolute;
            top: 10px;
            left: 20px;
            height: 50px;
        }
        .content {
            margin: 20px 0;
        }
        .unit {
            margin-bottom: 20px;
        }
        .unit h3 {
            margin: 0;
        }
        .footer {
            text-align: center;
            padding: 10px 0;
            color: #777777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="logo_url" alt="College Logo">
            <h1>Receipt for Unit Fee Payment</h1>
        </div>
        <div class="content">
            <p>Dear {{ $name }},</p>
            <p>Course: {{ $course_name }}</p>
            @foreach($fees as $index => $value)
            <div class="unit">
                <h3>Unit Name: Unit 1</h3>
                <p>Amount: ${{ $value->fee_amount }}</p>
                <p>Paid Date: {{ dateFormat($value->paid_date) }}</p>
            </div>
            @endforeach
        </div>
        <div class="footer">
            <p>Thank you for your payment.</p>
        </div>
    </div>
</body>
</html>