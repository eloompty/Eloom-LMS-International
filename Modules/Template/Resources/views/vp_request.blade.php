<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VP Request Letter_SIT40516 </title>
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

            <div class="date">Date: {{ $date }}</div>
            <h4 class="title">To whom it may concern </h4>

            <table>

                <tbody>
                    <tr>
                        <th>Student Name:</th>
                        <td>{{ $student_name }} </td>
                    </tr>
                    <tr>
                        <th>Student ID:</th>
                        <td>{{ $student_id }}</td>
                    </tr>
                    <tr>
                        <th>Enrolled Course:</th>
                        <td>{{ $course_name }} </td>
                    </tr>


                </tbody>
            </table>

            <div class="des">This is to confirm that <strong>{{ $student_name }}</strong> <span>(DOB: {{ $student_dob }})</span> is a full-time current student at {{ $company_name }}. </div>
            <div class="des">As part of the qualification, students are required to complete <strong> 360 hours </strong> of work placement in an operational commercial kitchen. This is to be undertaken for a minimum of <strong>6 hours </strong> per day and <strong>6 days </strong> per week over <strong>10 weeks</strong> .
            </div>
            <div class="table_border">

                <table>
                    <thead>
                        <tr>
                            <td>Qualification Code and Name</td>
                            <td>Proposed Start Date</td>
                            <td>Proposed Completion Date</td>
                            <td>Work Placement</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $course_name }}</td>
                            <td>{{ dateFormat($student_intake_course->starting_date) }}</td>
                            <td>{{ dateFormat($student_intake_course->ending_date) }}</td>
                            <td>360 hours</td>
                        </tr>


                    </tbody>
                </table>
            </div>
            <div class="des">Should you need any further information, please do not hesitate to contact me. </div>
            <div class="sincerely">
                <div class="regards">Kind regards, </div>
                <img src="{{ $regards_signature }}" alt="">
                <h5>{{ $regards_name }}</h5>
                <h5>{{ $regards_position }}</h5>
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