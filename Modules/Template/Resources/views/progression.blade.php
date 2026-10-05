<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progression </title>
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
            /* padding: 0 100px; */
            text-align: center;

        }

        .course_completion .date {
            text-align: left;
            font-weight: 500;
            font-size: 15px;
            margin: 10px 0;
        }

        h4.title {
            /* text-decoration: underline; */
            margin: 10px 0;
            font-size: 15px;
            text-align: left;
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
            font-size: 17px;
            margin: 5px 0;
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

        .table_border table {
            width: 100%;
        }

        .at_bottom a {
            font-size: 10px;
            color: blue;
        }

        .table_border {
            margin: 20px 0;
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
        <header class="header">

            <div class="logo">
                <img src="{{ $logo }}" alt="">
            </div>
        </header>
        <div class="mainpart">

            <div class="date">Date: {{ $date }}</div>
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
                        <th>Date of Birth:</th>
                        <td>{{ $student_dob }}</td>
                    </tr>

                </tbody>
            </table>
            <div class="des">To whom it may concern, </div>
            <h4 class="title">Re: Course Progress </h4>
            <div class="des"><?php echo $final_content; ?></div>
            <div class="table_border">

                <table>
                    <thead>
                        <tr>
                            <th>Qualification Code and Name</th>
                            <th>Course Commencement Date</th>
                            <th>Proposed Completion Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $course_name }}</td>
                            <td>{{ dateFormat($student_intake_course->starting_date) }}</td>
                            <td>{{ dateFormat($student_intake_course->ending_date) }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="des">Should you need any further information, please do not hesitate to contact me.</div>
            <div class="sincerely">
                <div class="regards">Kind regards,</div>
                <img src="{{ $regards_signature }}" alt="">

                <h5>{{ $regards_name }}</h5>
                <h5>{{ $regards_position }}</h5>
                <h6>{{ $regards_college_name }}</h6>
                <h6>{{ $regards_email }}</h6>
                <h6>{{ $regards_phone }}</h6>
            </div>
        </div>
        <footer class="footer">

            <div class="at_bottom">
                <div class="bottom_detail"><?php echo $footer; ?></div>
            </div>
        </footer>
    </div>
</body>

</html>