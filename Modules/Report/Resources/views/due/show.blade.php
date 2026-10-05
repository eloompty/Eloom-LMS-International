<html>
<head>
    <style>
        @page {
            margin: 0 25px;
        }

        body {
            text-align: center;
            font-family: 'Roboto', sans-serif;

        }

        header {
            position: fixed;
            top: 0;
            left: 0px;
            right: 0px;
            background-color: #fff;
            height: auto;
            min-height: 40px;
            overflow: visible;
            width: 100%;
        }

        h6 {
            margin: 0;
        }

        main {
            margin-top: 200px;
            margin-bottom: 170px;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0px;
            right: 0px;
            background-color: #fff;
            height: auto;
            min-height: 40px;
            overflow: visible;
            color: #000;
        }

        header h6 {
            margin: 0;
            font-weight: 700;
            font-size: 18px;
        }

        .logo img {
            height: 50px;
            width: 50px;
            object-fit: contain;
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

        table,
        table td,
        table th {
            border: 1px solid #dddddd;
            border-collapse: collapse;
            text-align: left;
            margin: auto;
            padding: 3px 5px;
            /* text-align: center; */
            font-size: 13px;
        }

        table tfoot td {
            border: none;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;

        }

        table tbody td {
            text-align: left;
        }

        /* .table_border{
            margin: 20px 0;
        } */
        p {
            page-break-after: always;
        }

        p:last-child {
            page-break-after: never;
        }
    </style>
    <title>Payment Due Report</title>
</head>

<body>
    <header>
        @if($template->layout)
            {!! renderTemplateZone($template, 'header', $placeholders) !!}
        @else
            <div class="logo"><img src="{{ asset($template->logo) }}"></div>
            <div><?php echo $template->header; ?></div>
        @endif
    </header>
    <footer>
        @if($template->layout)
            {!! renderTemplateZone($template, 'footer', $placeholders) !!}
        @else
            <div class="at_bottom"><div class="bottom_detail"><?php echo $template->footer; ?></div></div>
        @endif
    </footer>
    <main>
        @if(count($dues) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Intake</th>
                    <th>Course</th>
                    <!-- <th>Agent</th> -->
                    <th>Installment Name</th>
                    <th>Installment Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dues as $index => $value)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ userName('Student', $value->student_id) }}</td>
                    <td>{{ $value->studentIntakeCourseFee->intakeCourse->intake->name }}</td>
                    <td>{{ $value->studentIntakeCourseFee->intakeCourse->course->course_name }}</td>
                    <!-- <td>{{ $value->agent }}</td> -->
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->amount }}</td>
                    <td data-sort='{{ convertDate($value->due_date) }}'>{{ dateFormat($value->due_date) }}</td>
                    <td>@if ($value->due_date < date('Y-m-d')) Over Due @else Due @endif</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Intake</th>
                    <th>Course</th>
                    <!-- <th>Agent</th> -->
                    <th>Fee Installment</th>
                    <th>Installment Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </tfoot>
        </table>
        @else
        <h3>No Data Found</h3>
        @endif
    </main>
</body>

</html>