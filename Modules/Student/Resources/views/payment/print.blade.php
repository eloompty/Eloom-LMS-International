<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Document</title>
    <!-- <link href="https://fonts.cdnfonts.com/css/helvetica-neue-55" rel="stylesheet"> -->
    <style>
        /* * {
            font-family: 'Helvetica Neue', sans-serif !important;
            font-family: "Times New Roman", Times, serif !important;

        } */

        ul {
            padding: 0;
        }

        p {
            margin: 0;
            font-weight: 300 !important;
        }

        h3 {
            margin: 8px 0 2px;
            font-size: 14px;
            font-weight: 400;
        }

        body {
            width: 700px;
            margin: auto;
            /* border:1px solid #ccc; */
            padding: 10px;
        }

        table {
            margin: 20px 0;
        }

        .border_top {
            border: 1px solid #000;
            padding: 1px;
        }

        .box {
            border: 1px solid #b1b1b1;
            padding: 0 10px 10px;
            border-radius: 4px;
        }

        .box table tr td {
            font-size: 14px;
            padding: 7px 0 0 0;
            font-weight: 300;
            line-height: 20px;
        }

        .tax_invoice h2 {
            text-align: center;
            margin: 0;
            font-size: 19px;
            font-weight: 800;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            color: red;
        }

        .tax_invoice h6 {
            text-align: right;
            font-size: 14px;
            margin: 0;
            font-weight: 400;
            font-family: Arial, Helvetica, sans-serif;
        }

        .tax_invoice h6.to_whom {
            text-align: left;
            font-weight: 500;
        }

        .student_Detail ul li {
            list-style-type: none;
            padding: 10px 0;
        }

        .student_Detail ul li div {
            DISPLAY: INLINE-FLEX;
            font-weight: 600;

        }

        .student_Detail h4 {
            font-weight: 600;
        }

        .student_Detail h3 {
            padding: 30px 0 0 0;
            margin: 0;
        }

        .taxinvoice_full_page .logo {
            text-align: left;
            margin: 4px auto;
        }

        .taxinvoice_full_page .logo img {
            height: 50px;
            width: auto;
            object-fit: contain;
        }

        .regards table tr td {

            font-size: 16px;
            line-height: 27px;
            padding: 7px 0 0 0;
            font-weight: 300;
        }

        table tr td {
            font-size: 14px;
            padding: 7px 0 0 0;
            font-weight: 500;
        }

        table,
        th,
        td {
            border-collapse: collapse;
        }

        .tax_invoice table {
            width: 100%;
        }

        .student_Detail table {
            width: 70%;
        }

        .detail_tab table {
            width: 36%;
        }

        .box table {
            margin: 0;
            line-height: inherit;
        }
    </style>
</head>

<body>

    <div class="taxinvoice_full_page">

        <div class="logo">
            <img src="{{ asset(getSettingValue('offer_college_logo')) }}" alt="">
        </div>

        <div class="tax_invoice">
            <div class="border_top"></div>
            <table>
                <tr>
                    <th colspan="1">
                        <h2>
                            Tax Invoice
                        </h2>
                    </th>
                </tr>
                <tr>
                <tr>

                    <td>
                        <h6>Date: {{ dateFormat('Y-m-d') }}</h6>
                    </td>

                </tr>
                <tr>
                    <td>
                        <h6>Invoice No.: D 516 </h6>
                    </td>

                </tr>
                <tr>
                    <td>
                        <h6 class="to_whom">To : Finance Department</h6>
                    </td>

                </tr>
                <tr>
                    <td>
                        <h3>{{ $payment->intakeCourse->intake->name }}</h3>
                    </td>

                </tr>
                </tr>
            </table>

            <div class="border_top"></div>

        </div>

        <div class="student_Detail">
            <table>
                <tr>
                    <td>
                        Student Name :
                    </td>
                    <td>
                        {{ userName('Student', $payment->student_id) }} (DOB : {{dateFormat($payment->student->date_of_birth)}})
                    </td>
                </tr>
                <tr>
                    <td>
                        Student ID :
                    </td>
                    <td>
                        {{ $payment->student->student_id }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Course:
                    </td>
                    <td>
                        {{ $payment->intakeCourse->course->course_name }}
                    </td>
                </tr>
                <!-- <tr>
                    <td>
                        Payment for :
                    </td>
                    <td>
                        1st Semister - Due Date (24/08/2022)
                    </td>
                </tr> -->
                <tr>
                    <td>
                        Total Amount :
                    </td>
                    <td>
                        ${{ $payment->total_amount }}
                    </td>
                </tr>
            </table>
        </div>

        <p>
            Thank you for your attention.

        </p>
        <br>
        <div class="box">
            <table>
                <tr>
                    <td> Payment should be made in Australian Dollars (AUD$) by cash, cheque or telegraphic transfer and made cheque payable to Oasis Education</td>
                </tr>
                <tr>
                    <td>
                        A telegraphic transfer (Australian Dollars) to:
                    </td>
                </tr>
                <tr>
                    <td>
                        ----------
                    </td>
                </tr>
                <tr>
                    <td>
                        Account Number <span> ------- </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Account Name Oasis Education PTY LTD
                    </td>
                </tr>
                <tr>
                    <td>
                        Bank Westpac Banking Cooperation

                    </td>
                </tr>
                <tr>
                    <td>
                        Branch Kingsford, NSW 2032

                    </td>
                </tr>
            </table>
            </h3>
                <!-- <li>
                Comission : </div>
                $600.00 </div>
            </li> -->
                <!-- <li>
                GST :  </div>
                $600.00 </div>
            </li> -->
            </ul>
            <!-- <h4>
            Total Payable : $660.00

            </h3> -->
                <!-- <li>
                Comission : </div>
                $600.00 </div>
            </li> -->
                <!-- <li>
                GST :  </div>
                $600.00 </div>
            </li> -->
            </ul>
            <!-- <h4>
            Total Payable : $660.00

        </div>
        <div class="regards">

            <table>
                <tr>
                    <td>
                        Regards,<br>
                        Administrator
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>