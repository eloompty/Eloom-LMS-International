<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Term Break </title>
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
            text-align: left;
            font-weight: 500;
            font-size: 15px;
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

            <div class="date">{{ $date }}</div>

            <h4 class="title">{{ $company_name }} – Term Break </h4>
            <div class="des">TO WHOM IT MAY CONCERN, </div>
            <div class="des">This is to confirm that <strong> {{ $student_name }} (Student ID {{ $student_id }}) </strong> is a current student at {{ $company_name }}.
                We hereby inform you that {{ $company_name }} will be on a term break from <strong> {{ $term_break_from }} </strong> to <strong>{{ $term_break_to }} </strong> inclusive.
            </div>
            <div class="des">Should you have any questions please do not hesitate to contact us.</div>

            <div class="sincerely">
                <div class="regards">Regards, </div>
                <img src="{{ $regards_signature }}" alt="">
                <h5>{{ $regards_name }}</h5>
                <small>{{ $regards_position }}</small>
                <h6>{{ $regards_college_name }}</h6>
                <h6>{{ $regards_email }}</h6>
                <h6>{{ $regards_phone }}</h6>
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