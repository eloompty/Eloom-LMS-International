<!DOCTYPE html>
<html>

<head>
    <title>Student Offer</title>
    <style>
        /* Styles go here */

        .page-header,
        .page-header-space {
            height: 100px;
        }

        .page-footer,
        .page-footer-space {
            height: 50px;

        }

        .page-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            /* border-top: 1px solid black; */
            /* for demo */
            /* background: yellow; */
            /* for demo */
        }

        .page-header {
            position: fixed;
            top: 0mm;
            width: 100%;
            /* border-bottom: 1px solid black; */
            /* for demo */
            /* background: yellow; */
            /* for demo */
        }

        .page {
            page-break-after: always;
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        .smallf {
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
        }

        .smallff {
            font-family: "Times New Roman", Times, serif;
            font-size: 10px;
        }

        hr.thin {
            height: 1px;
            border: 0;
            color: #333;
            background-color: #333;
            width: 80%;
        }

        .tdbg {
            background: #d9d9d9;
        }

        @page {
            margin: 20mm, 20mm;
        }

        @media print {
            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            button {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="page-header" style="text-align: center">
        <table width="700px;">
            <tr>
                <td align="right">
                    @if (!empty($offerCollegeLogo))
                    <img src="{{ !empty($isPdf) ? $offerCollegeLogo : asset($offerCollegeLogo) }}" height="80" alt="">
                    @endif
                </td>
            </tr>
        </table>
        <br />
        <!-- <button type="button" onClick="window.print()" style="background: pink">
            PRINT!
        </button> -->
    </div>

    <div class="page-footer">
        <table width="700px">
            <tr>
                <td class="smallff" colspan="2" align="center">
                    {{ $company->company_name }}, RTO Code {{ $company->rto_no }} CRICOS Provider Code {{ $company->rto_no }}<br>
                    Address : {{ fullAddress('Company', $company->id) }}<br>
                    Email: {{ getSettingValue('offer_organisation_email') ?: $company->email }} Phone: {{ getSettingValue('offer_organisation_phone') }}<br><br><br><br>
                </td>
            </tr>
        </table>
    </div>

    <table>

        <thead>
            <tr>
                <td>
                    <!--place holder for the fixed-position header-->
                    <div class="page-header-space"></div>
                </td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <!--*** CONTENT GOES HERE ***-->
                    <div class="page">
                        <table width="700px;">
                            <tr>
                                <td class="smallf" colspan="2">
                                    <b>INTERNATIONAL STUDENT LETTER OF OFFER & AGREEMENT</b><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="smallf" colspan="2">
                                    <p>Date: @if ($letter->issue_date == NULL) {{ dateFormat($letter->created_at) }} @else {{ dateFormat($letter->issue_date) }} @endif</p><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="smallf" colspan="2">
                                    {{ userName('Student', $letter->student_id) }}<br>
                                </td>
                            </tr>
                            <tr>
                                <td class="smallf" colspan="2">
                                    {{ $letter->student->overseas_address }} {{ optional($letter->student->overseasCountry)->name }}<br>
                                </td>
                            </tr>
                            <tr>
                                <td class="smallf" colspan="2"> Offer NUMBER: &nbsp; {{ offerNumber($letter->id) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="smallf" colspan="2">
                                    <br>
                                    <p>Dear {{ $letter->student->first_name }},<br><br>

                                        Thank you for your application to enrol with {{ $company->company_name }}. We have reviewed your application and take great pleasure in offering you a place in the course you have applied for. </p>
                                </td>
                            </tr>
                        </table>
                        <table cellspacing="0" cellpadding="1" border="1" width="700px">
                            <tr>
                                <td class="smallf tdbg" width="50" height="30"> &nbsp;<b>Course Code</b></td>
                                <td class="smallf tdbg" width="150"> &nbsp;<b>Course</b></td>
                                <td class="smallf tdbg" width="45"> &nbsp;<b>CRICOS Course Code</b></td>
                                <td class="smallf tdbg" width="50"> &nbsp;<b>Start Date</b></td>
                                <td class="smallf tdbg" width="50"> &nbsp;<b>Finish Date</b></td>
                                <td class="smallf tdbg" width="60"> &nbsp;<b>Duration</b></td>
                            </tr>
                            @foreach($student_courses as $course)
                            <tr>
                                <td class="smallf" width="50" height="25">{{ $course->intakeCourse->course->course_code }}</td>
                                <td class="smallf" width="150"> {{ $course->intakeCourse->course->course_name }}</td>
                                <td class="smallf" width="45"> {{ $course->intakeCourse->course->cricos_code }}</td>
                                <td class="smallf" width="50"> {{ dateFormat($course->intakeCourse->starting_date) }}</td>
                                <td class="smallf" width="50"> {{ dateFormat($course->intakeCourse->ending_date) }}</td>
                                <td class="smallf" width="60"> {{ $course->intakeCourse->duration }} Weeks</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="smallf" colspan="6">{{ $letter->credit_description }}</td>
                            </tr>

                        </table>
                        <div style="clear:both;"></div>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="smallf">
                                    You will find the details of your enrolment along with the terms and conditions attached. If you would like to take up the offer of this place, you should do so immediately. To secure your place in the course, please complete the Written Agreement included at the end of this document to indicate your acceptance and send it back within {{ dateformat($letter->expiry_date) }}.
                                    <br><br>

                                    Please pay attention to Course Fee Overview, Payment Plan and Schedule of Charges attached. Please also pay attention to document requirements in Conditions to be fulfilled section, if any. If you require further advice or clarification regarding the attached documents, please contact us at our office.
                                    <br> <br>
                                    The Orientation date is {{ dateformat($student_courses[0]->intakeCourse->intake->orientation_date) }}. The class start date is {{ dateformat($student_courses[0]->intakeCourse->starting_date) }}. Be present in the premise by 9.00 am. <br><br>
                                    Your classes will be held in the following address: @if ($student_courses[0]->intakeCourse->course->deliverSite != NULL) {{ $student_courses[0]->intakeCourse->course->deliverSite->companyDeliverySite->site_name }} @else - @endif<br>
                                    We look forward to welcoming you to {{ $company->company_name }} and wish you all the best with your studies.
                                    <br><br>
                                    Kind regards,<br><br><br>
                                    @if (!empty($offerSignature))
                                    <img src="{{ !empty($isPdf) ? $offerSignature : asset($offerSignature) }}" height="25" alt="">
                                    @endif
                                    <br>
                                    {{ getSettingValue('offer_signed_by_name') }}<br>
                                    {{ getSettingValue('offer_signed_by_designation') }}<br>
                                    {{ $company->company_name }} <br><br>

                                    {{ $company->company_name }}, RTO Code {{ $company->rto_no }} CRICOS Provider Code {{ $company->rto_no }}<br>
                                    Address : {{ fullAddress('Company', $company->id) }}<br>
                                    Email: {{ getSettingValue('offer_organisation_email') ?: $company->email }} Phone: {{ getSettingValue('offer_organisation_phone') }}<br><br><br>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="page">
                        <div style="clear:both;"></div>
                        <p class="smallf"><b>Course Fees Overview</b></p>
                        <table cellspacing="0" cellpadding="1" border="1" width="700px">
                            @foreach($student_fees as $index => $fee)
                            <tr>
                                <td class="smallf tdbg" width="50%"> &nbsp;Enrolment Application Fee (non-refundable)</b></td>
                                <td class="smallff">
                                    $ {{ $fee->enrollment_fee }}
                                </td>
                            </tr>
                            <tr>
                                <td class="smallf tdbg" height="18"> &nbsp;Tuition Fee {{ $fee->intakeCourse->course->course_name }} </td>
                                <td class="smallff">$ {{ $fee->fee }}</td>
                            </tr>
                            <tr>
                                <td class="smallf tdbg" height="18"> &nbsp;Non-Tuition Fee: Materials Fee ({{ $fee->intakeCourse->course->course_name }}) </td>
                                <td class="smallff">$ {{ $fee->material_fee }}</td>
                            </tr>
                            <tr>
                                <td class="smallf tdbg" height="18"> &nbsp;Total Course Fees </td>
                                <td class="smallff">$ {{ $fee->enrollment_fee + $fee->fee + $fee->material_fee }}</td>
                            </tr>
                            <?php if (!empty($offerdata['oshc_fee'])) { ?>
                                <tr>
                                    <td class="smallf tdbg" height="18"> &nbsp;Overseas Student Health Cover </td>
                                    <td class="smallff">$ <?php echo '$' . $offerdata['oshc_fee']; ?></td>
                                </tr>
                            <?php } ?>

                            @php
                            $enrollment[] = $fee->enrollment_fee;
                            $totatFee[] = $fee->fee;
                            $materialFee[] = $fee->material_fee;
                            $feeInstallments = $fee->fee_installments;
                            $firstFeeInstallment = $feeInstallments->first();
                            $first_instalments[] = $firstFeeInstallment ? $firstFeeInstallment->amount : 0;
                            if ($firstFeeInstallment) {
                                $total_first_installments[] = $firstFeeInstallment->studentIntakeCourseFee->intakeCourse->course->course_name . ': $'. $firstFeeInstallment->amount;
                                $cricos[] = $firstFeeInstallment->studentIntakeCourseFee->intakeCourse->course->cricos_code;
                            }
                            $installments[] = $feeInstallments->where('name', '!=', 'First Installment');
                            @endphp
                            @endforeach
                            @php
                            $total_enrollment = array_sum($enrollment);
                            $total_fee = array_sum($totatFee);
                            $total_material_fee = array_sum($materialFee);
                            $first_installment = array_sum($first_instalments);
                            @endphp
                        </table>
                        <p class="smallf">Proforma Student Invoice</p>
                        <div id="center">
                            <!-- here -->

                            <table cellspacing="0" cellpadding="0" border="1">
                                <tr>
                                    <td class="smallf tdbg" width="90" height="18"> &nbsp;<b>Enrollment Fee</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Tuition Fee</b></td>
                                    <td class="smallf tdbg" width="65"> &nbsp;<b>OSHC</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Others (Student <br>ID, Material Fee)</b></td>
                                    <td class="smallf tdbg" width="80"> &nbsp;<b>Discounts</b></td>
                                    <td class="smallf tdbg" width="90"> &nbsp;<b>Total Summary</b></td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="90" height="16"> &nbsp;${{ $total_enrollment }}</td>
                                    <td class="smallf" width="100"> &nbsp;${{ $total_fee }}</td>
                                    <td class="smallf" width="65"> &nbsp;</td>
                                    <td class="smallf" width="100"> &nbsp;${{ $total_material_fee }}</b></td>
                                    <td class="smallf" width="80"> &nbsp;$0.00</td>
                                    <td class="smallf" width="90"> &nbsp;${{ $total_enrollment + $total_fee + $total_material_fee }}</td>
                                </tr>
                            </table>
                        </div><br>
                        <div id="center">
                            <br>
                            <table cellspacing="0" cellpadding="0" border="1">
                                <tr>
                                    <td class="smallf tdbg" width="50" height="18"> &nbsp;<b>Payment No.</b></td>
                                    <td class="smallf tdbg" width="150"> &nbsp;<b>Due Date</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Tuition Fees</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Non-Tuition Fees</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Total Amount</b></td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50" height="16"> &nbsp;1</td>
                                    <td class="smallf" width="80"> &nbsp;Due on acceptance of offer

                                    </td>
                                    <td class="smallf" width="100">
                                        Total: $ {{ $first_installment }};
                                        @foreach ($total_first_installments as $fi)
                                        {{ $fi }}
                                        @endforeach
                                    </td>
                                    <td class="smallf" width="125"> &nbsp;${{ $total_enrollment + $total_material_fee }}
                                    <td class="smallf" width="90"> &nbsp;{{ $total_enrollment + $total_fee + $total_material_fee }}
                                </tr>
                            </table>
                            <br>
                            <table cellspacing="0" cellpadding="0" border="1">
                                <tr>
                                    <td class="smallf tdbg" colspan="7"><b>Payment Schedule</b></td>
                                </tr>
                                <tr>
                                    <td class="smallf tdbg" width="30" height="18"> &nbsp;<b>Payment No.</b></td>
                                    <td class="smallf tdbg" width="135" height="18"> &nbsp;<b>Course</b></td>
                                    <td class="smallf tdbg" width="80"> &nbsp;<b>Due Date</b></td>
                                    <td class="smallf tdbg" width="60"> &nbsp;<b>Tuition Fees</b></td>
                                    <td class="smallf tdbg" width="65"> &nbsp;<b>Non-Tuition Fees</b></td>
                                    <td class="smallf tdbg" width="30"> &nbsp;<b>Discounts</b></td>
                                    <td class="smallf tdbg" width="80"> &nbsp;<b>Total</b></td>
                                </tr>
                                @php $index = 2 @endphp
                                @foreach($installments as $key => $installment)
                                @foreach($installment as $number => $value)
                                <tr>
                                    <td class="smallf" width="30" height="16"> &nbsp;{{ $index }}</td>
                                    <td class="smallf" width="135" height="16"> &nbsp;{{ $value->studentIntakeCourseFee->intakeCourse->course->course_code }} {{ $value->studentIntakeCourseFee->intakeCourse->course->course_name }}</td>
                                    <td class="smallf" width="80"> &nbsp;{{ dateFormat($value->due_date) }}</td>
                                    <td class="smallf" width="60"> &nbsp;${{ $value->amount }}</td>
                                    <td class="smallf" width="65"> &nbsp;$0</b></td>
                                    <td class="smallf" width="30"> &nbsp;$0</td>
                                    <td class="smallf" width="80"> &nbsp;${{ $value->amount }}</td>
                                </tr>
                                @php $index++ @endphp
                                @endforeach
                                @endforeach

                                <tr>
                                    <td colspan="7">&nbsp;</td>
                                </tr>
                            </table>


                        </div>
                    </div>
                    <div class="page">
                        <div>
                            <table width="700px;">

                                <tr>
                                    <td class="smallf"><b>Payment Details</b><br><br>
                                        I am paying Fees to confirm enrolment by the method indicated below: (please tick one box)<br>

                                        <input id="click1" name="click1" type="checkbox" />Bank Cheque or Bank Draft (attached) payable to {{ getSettingValue('offer_bank_account_name') ?: $company->company_name }}<br>
                                        <input id="click2" name="click2" type="checkbox" /> Bank Transfer (a copy of the bank receipt is attached)<br><br>
                                        Account Name: {{ getSettingValue('offer_bank_account_name') }}<br>
                                        Bank Name: {{ getSettingValue('offer_bank_name') }}<br>
                                        BSB: {{ getSettingValue('offer_bank_bsb') }}<br>
                                        Account Number: {{ getSettingValue('offer_bank_account_number') }}<br>
                                        <br>
                                        <br>

                                        Important:<br>

                                        In the payment message or instruction, please indicate clearly your full name, so we can identify your payment easily.<br><br>

                                        <input id="click3" name="click3" type="checkbox" /> <label for="click3"> <b>Credit Card</b><br>
                                            The undersigned authorises {{ $company->company_name }} to debit the credit card as indicated below:<br>


                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf"><br><br>Credit Card Type: Visa Master Card <br>
                                        Credit Card No.<br> CCV No*: <br>
                                        Expiry Date: <br>
                                        Name on Card: <br>
                                        Cardholders Signature: <br><br>


                                        *This is the three digit number printed near the signature panel on the back of the card
                                        <br>
                                    </td>
                                </tr>


                            </table>
                            <table width="700px" class="smallff">
                                <tr>
                                    <td class="smallf">
                                        I will be studying at {{ $company->company_name }} on a:<br>
                                        Student Visa. I will apply for my visa at the Australian Diplomatic Mission or DIBP office located in<br><br><br>

                                        __________________________[insert the location, address and hours open]<br><br><br>

                                        Nationality:_______________________________ Passport No:__________________________<br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallff">
                                        <b>Declaration.</b><br>

                                        I understand this Acceptance constitutes a written agreement with {{ $company->company_name }}. I have read and understood the terms and Conditions of Enrolment as detailed in the Acceptance Agreement, and I agree to abide by them. I declare that all information I have provided is true and correct and I am now paying the fees to confirm enrolment.
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" colspan="2" height="30"><br><br><br><br><br>

                                        _____________________________________ &nbsp;&nbsp;&nbsp;&nbsp;________________________________<br><br>

                                        Signature of Student* &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;
                                        Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/<br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallff">
                                        Student's signature must be the same as on their passport (please supply a copy)<br>
                                        <br>

                                        Please return your signed course Acceptance Agreement and completed Payment Details page with evidence of your payment to the Admissions Coordinator email at {{ getSettingValue('offer_organisation_email') ?: $company->email }} or to a relevant campus at the address shown below:<br><br>

                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="page">
                        <p>&nbsp;</p>
                        <p class="smallf"><b>Letter of Offer / Written Agreement</b></p>
                        <p class="smallf">The details of your offer are as stated in the table below. This document ensures your Consumer rights are protected under Australian law. <br>Please check that these are correct and contact the person referred to in the cover letter of this offer if any changes are required.</p>
                        <div id="center">
                            <table cellspacing="0" cellpadding="1" border="1" width="700px">
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;RTO Legal Name:</td>
                                    <td class="smallff">
                                        &nbsp;<b>{{ $company->company_name }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;RTO Name:</td>
                                    <td class="smallff">
                                        &nbsp;<b>{{ $company->company_name }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;RTO Number:</td>
                                    <td class="smallff">
                                        &nbsp;{{ $company->rto_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;CRICOS Provider Number:</td>
                                    <td class="smallff">
                                        &nbsp;{{ getSettingValue('offer_cricos_provider_code') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;CRICOS Course CODE:</td>
                                    <td class="smallff">
                                        &nbsp;{{ implode(",", $cricos) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;Student Name:</td>
                                    <td class="smallff">
                                        &nbsp;{{ userName('Student', $letter->student_id) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;Passport Number: </td>
                                    <td class="smallff">
                                        &nbsp;{{ $letter->student->passport_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;Offer Number:</td>
                                    <td class="smallff">
                                        &nbsp;{{ $letter->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;Address:</td>
                                    <td class="smallff">
                                        &nbsp;@if (optional($student_fees->first())->type == 'Onshore')
                                        {{ fullAddress('Student', $letter->student_id) }}
                                        @else
                                        {{ $letter->student->overseas_address }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%"> &nbsp;Date of Birth:</td>
                                    <td class="smallff">
                                        &nbsp;{{ dateFormat($letter->student->date_of_birth) }}
                                    </td>
                                </tr>
                            </table>
                        </div><br>
                        <div id="center">
                            <table cellspacing="0" cellpadding="1" border="1" width="700px">
                                <tr>
                                    <td class="smallf tdbg" width="50" height="30"> &nbsp;<b>Course Code</b></td>
                                    <td class="smallf tdbg" width="150"> &nbsp;<b>Course</b></td>
                                    <td class="smallf tdbg" width="45"> &nbsp;<b>CRICOS Course Code</b></td>
                                    <td class="smallf tdbg" width="50"> &nbsp;<b>Start Date</b></td>
                                    <td class="smallf tdbg" width="50"> &nbsp;<b>Finish Date</b></td>
                                    <td class="smallf tdbg" width="60"> &nbsp;<b>Duration</b></td>
                                </tr>
                                @foreach($letter->student->intake as $intake_course)
                                <tr>
                                    <td class="smallf" width="50" height="25"> &nbsp;{{ $intake_course->intakeCourse->course->course_code }}</td>
                                    <td class="smallf" width="150"> &nbsp;{{ $intake_course->intakeCourse->course->course_name }}</td>
                                    <td class="smallf" width="45"> &nbsp;{{ $intake_course->intakeCourse->course->cricos_code }}</td>
                                    <td class="smallf" width="50"> &nbsp;{{ dateFormat($intake_course->intakecourse->starting_date) }}</td>
                                    <td class="smallf" width="50"> &nbsp;{{ dateFormat($intake_course->intakecourse->ending_date) }}</td>
                                    <td class="smallf" width="60"> &nbsp;{{ $intake_course->intakecourse->duration }} Weeks</td>
                                </tr>
                                @endforeach
                            </table>
                        </div><br>
                        <div id="center">
                            <table cellspacing="0" cellpadding="1" border="1" width="700px">
                                <tr>
                                    <td class="smallf" width="50%" height="20"> &nbsp;Orientation Date:</b></td>
                                    <td class="smallff" colspan="2">
                                        &nbsp;{{ dateFormat($student_courses[0]->intakecourse->intake->orientation_date) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%" height="20"> &nbsp;Location:</b></td>
                                    <td class="smallff" colspan="2">
                                        &nbsp;@if ($student_courses[0]->intakeCourse->course->deliverSite != NULL) {{ $student_courses[0]->intakeCourse->course->deliverSite->companyDeliverySite->site_name }} @else - @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50%" height="20"> &nbsp;Mode of Study :</b></td>
                                    <td class="smallff">
                                        &nbsp;Face to Face
                                    </td>
                                    <td class="smallff">
                                        &nbsp;<!-- No Online, work-placement or community/research- based training -->
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td class="smallf" width="50%" height="25"> &nbsp;Recognition of Prior Learning application: </b><br>Yes / No</td>
                                    <td class="smallff">
                                        &nbsp;Credit application: <br>Yes / No
                                    </td>
                                    <td class="smallff">
                                        &nbsp;
                                    </td>
                                </tr> -->
                            </table>
                        </div>
                        @if ($letter->student->studentAgent != NULL)
                        <p class="smallf"><b>Agent Details</b></p>
                        <div id="center">
                            <table cellspacing="0" cellpadding="1" border="1" width="700px">
                                <tr>
                                    <td class="smallf" width="50%" height="30"> &nbsp;Agent Name:</b><br>{{ $letter->student->studentAgent->agent->company_name }}</td>
                                    <td class="smallff">
                                        &nbsp;Address: {{ $letter->student->studentAgent->agent->address }} {{ $letter->student->studentAgent->agent->city }}<br>
                                        &nbsp;Phone: {{ $letter->student->studentAgent->agent->mobile }} {{ $letter->student->studentAgent->agent->office_phone }}<br>
                                        &nbsp;Website: {{ $letter->student->studentAgent->agent->url }}<br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        @endif
                        <p class="smallf"><b>Conditions to be Fulfilled</b></p>
                        <div id="center">
                            <table cellspacing="0" cellpadding="1" border="1" width="700px">
                                <tr>
                                    <td class="smallf" height="40">{{ $letter->condition_description }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="page">
                        <div id="pg1">
                            <table width="700px;">
                                <!-- <tr>
                                    <td class="smallf" colspan="2">
                                        Letter of Offer / Student Agreement
                                    </td>
                                </tr> -->
                                <tr>
                                    <td class="smallf" colspan="2">
                                        <p>{{ dateFormat($letter->created_at) }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="125">
                                        <b>Student Name:</b>
                                    </td>
                                    <td class="smallf">
                                        {{ userName('Student', $letter->student_id) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="125">
                                        <b>Applicant ID:</b>
                                    </td>
                                    <td class="smallf">
                                        {{ $letter->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="125">
                                        <b>Date of Birth:</b>
                                    </td>
                                    <td class="smallf">
                                        {{ dateFormat($letter->student->date_of_birth) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" colspan="2" align="center"><br><b>Proforma Student Invoice</b></td>
                                </tr>
                            </table>
                            <table cellspacing="0" cellpadding="0" border="1">
                                <tr>
                                    <td class="smallf tdbg" width="90" height="18"> &nbsp;<b>Enrollment Fee</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Tuition Fee</b></td>
                                    <td class="smallf tdbg" width="65"> &nbsp;<b>OSHC</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Others (Student <br>ID, Material Fee)</b></td>
                                    <td class="smallf tdbg" width="80"> &nbsp;<b>Discounts</b></td>
                                    <td class="smallf tdbg" width="90"> &nbsp;<b>Total Summary</b></td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="90" height="16"> &nbsp;${{ $total_enrollment }}</td>
                                    <td class="smallf" width="100"> &nbsp;${{ $total_fee }}</td>
                                    <td class="smallf" width="65"> &nbsp;</td>
                                    <td class="smallf" width="100"> &nbsp;${{ $total_material_fee }}</b></td>
                                    <td class="smallf" width="80"> &nbsp;$0.00</td>
                                    <td class="smallf" width="90"> &nbsp;${{ $total_enrollment + $total_fee + $total_material_fee }}</td>
                                </tr>
                            </table>
                            <br>
                            <table cellspacing="0" cellpadding="0" border="1">
                                <tr>
                                    <td class="smallf tdbg" width="50" height="18"> &nbsp;<b>Payment No.</b></td>
                                    <td class="smallf tdbg" width="150"> &nbsp;<b>Due Date</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Tuition Fees</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Non-Tuition Fees</b></td>
                                    <td class="smallf tdbg" width="100"> &nbsp;<b>Total Amount</b></td>
                                </tr>
                                <tr>
                                    <td class="smallf" width="50" height="16"> &nbsp;1</td>
                                    <td class="smallf" width="80"> &nbsp;Due on acceptance of offer

                                    </td>
                                    <td class="smallf" width="100">
                                        Total: $ {{ $first_installment }};
                                        @foreach ($total_first_installments as $fi)
                                        {{ $fi }}
                                        @endforeach
                                    </td>
                                    <td class="smallf" width="125"> &nbsp;${{ $total_enrollment + $total_material_fee }}
                                    <td class="smallf" width="90"> &nbsp;{{ $total_enrollment + $total_fee + $total_material_fee }}
                                </tr>
                            </table>
                            <br>
                            <table cellspacing="0" cellpadding="0" border="1">
                                <tr>
                                    <td class="smallf tdbg" colspan="7"><b>Payment Schedule</b></td>
                                </tr>
                                <tr>
                                    <td class="smallf tdbg" width="30" height="18"> &nbsp;<b>Payment No.</b></td>
                                    <td class="smallf tdbg" width="135" height="18"> &nbsp;<b>Course</b></td>
                                    <td class="smallf tdbg" width="80"> &nbsp;<b>Due Date</b></td>
                                    <td class="smallf tdbg" width="60"> &nbsp;<b>Tuition Fees</b></td>
                                    <td class="smallf tdbg" width="65"> &nbsp;<b>Non-Tuition Fees</b></td>
                                    <td class="smallf tdbg" width="30"> &nbsp;<b>Discounts</b></td>
                                    <td class="smallf tdbg" width="80"> &nbsp;<b>Total</b></td>
                                </tr>
                                @foreach($installments as $key => $installment)
                                @foreach($installment as $number => $value)
                                <tr>
                                    <td class="smallf" width="30" height="16"> &nbsp;{{ $number+1 }}</td>
                                    <td class="smallf" width="135" height="16"> &nbsp;{{ $value->studentIntakeCourseFee->intakeCourse->course->course_code }} {{ $value->studentIntakeCourseFee->intakeCourse->course->course_name }}</td>
                                    <td class="smallf" width="80"> &nbsp;{{ dateFormat($value->due_date) }}</td>
                                    <td class="smallf" width="60"> &nbsp;${{ $value->amount }}</td>
                                    <td class="smallf" width="65"> &nbsp;$0</b></td>
                                    <td class="smallf" width="30"> &nbsp;$0</td>
                                    <td class="smallf" width="80"> &nbsp;${{ $value->amount }}</td>
                                </tr>
                                @endforeach
                                @endforeach

                                <tr>
                                    <td colspan="7">&nbsp;</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <div class="page">
                        <div id="pg1">
                            @php($offerTerms = getSettingValue('offer_terms_and_conditions'))
                            <table width="700px;" cellspacing="5px">
                                <tr>
                                    <td class="smallf">
                                        <p>
                                            <b>Terms and Conditions of Enrolment</b><br><br>

                                            @if (!empty($offerTerms))
                                            {!! nl2br(e($offerTerms)) !!}
                                            @else
                                            [ Enrolment terms and conditions have not been configured. Set the
                                            <b>offer_terms_and_conditions</b> setting to the terms your organisation issues to
                                            students. This template ships without terms on purpose: the code of conduct, fees and
                                            refunds policy, complaints and appeals process, and any regulatory disclosures are
                                            specific to each provider and jurisdiction, and must be supplied by the provider. ]
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <table width="700px">
                                <tr>
                                    <td class="smallff">
                                        <b>Declaration.</b><br>

                                        I understand this Acceptance constitutes a written agreement with {{ $company->company_name }}. I have read and understood the terms and Conditions of Enrolment as detailed in the Acceptance Agreement, and I agree to abide by them. I declare that all information I have provided is true and correct and I am now paying the fees to confirm enrolment.
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallf" colspan="2" height="30"><br><br>

                                        _____________________________ &nbsp;&nbsp;__________________________<br><br>

                                        Signature of Student* &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/<br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="smallff">
                                        Student's signature must be the same as on their passport (please supply a copy)<br>
                                        <br>

                                        Please return your signed course Acceptance Agreement and completed Payment Details page with evidence of your payment to the Admissions Coordinator email at {{ getSettingValue('offer_organisation_email') ?: $company->email }} or to a relevant campus at the address shown below:<br><br>

                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <td>
                    <!--place holder for the fixed-position footer-->
                    <div class="page-footer-space"></div>
                </td>
            </tr>
        </tfoot>

    </table>

    @if (!empty($autoPrint))
    <script>
        window.addEventListener('load', function () { window.print(); });
    </script>
    @endif
</body>

</html>
