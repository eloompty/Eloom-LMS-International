<?php

namespace Modules\Student\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Student\Entities\StudentOfferTemplate;

class OfferLetterTemplateSeeder extends Seeder
{
    /**
     * Seed the default "Offer Letter and Student Agreement" template,
     * reproducing the standard RTO offer letter as editable rich-text
     * sections (with merge tokens) plus dynamic course/subject/fees/payment blocks.
     */
    public function run()
    {
        $i = 0;
        $rt = function ($title, $html) use (&$i) {
            return ['id' => 'seed-' . (++$i), 'type' => 'richtext', 'title' => $title, 'content' => $html];
        };
        $block = function ($type, $title) use (&$i) {
            return ['id' => 'seed-' . (++$i), 'type' => $type, 'title' => $title];
        };
        $break = function () use (&$i) {
            return ['id' => 'seed-' . (++$i), 'type' => 'page_break'];
        };

        $sections = [];

        // ---- Offer Letter ----
        $sections[] = $rt('Offer Letter', <<<'HTML'
<p style="font-size:16px;"><b>Offer Letter and Student Agreement</b></p>
<p><b>Offer Letter</b></p>
<p>Date: {{issue_date}}</p>
<p>Dear {{student_name}}</p>
<p>We are delighted to inform you that your application to enrol with us has been accepted.</p>
<p>Please carefully check all the details of your enrolment and carefully read the terms and conditions. If there are any changes to be made to your details or any information that you are unsure about, please contact us.</p>
<p>Once you are satisfied that all of the information is correct and you understand the terms and conditions you are agreeing to, please complete the declaration at the end and return it to us.</p>
<p>Once we receive your declaration, we will send you a tax invoice which you should pay immediately to secure your place.</p>
<p>Upon completion of all of the above, we will email you to confirm your enrolment.</p>
<p>We look forward to welcoming you.</p>
<p>Kind regards,<br>{{signed_by_name}}<br>{{signed_by_designation}}<br>Email: {{organisation_email}}<br>Phone: {{organisation_phone}}</p>
HTML);

        // ---- Accepting this offer + bank account ----
        $sections[] = $rt('Accepting this offer', <<<'HTML'
<p><b>Accepting this offer</b></p>
<p>To confirm your enrolment into this course you are required to:</p>
<ul>
<li>check all your personal details to ensure they are correct</li>
<li>carefully read all of the information in this Student Agreement. If there is anything that you do not understand, please contact us</li>
<li>sign and date this agreement and return it to us once you are satisfied that all details are correct and that you understand the terms and conditions.</li>
</ul>
<p>You should also keep a copy of this agreement for your records as stated under the Student Declaration.</p>
<p>Once we receive your signed agreement, we will send you an invoice for immediate payment and then we will confirm your enrolment.</p>
<p>All payments are to be made into the following account:</p>
<table cellspacing="0" cellpadding="4" border="1" width="700px">
<tr><td class="smallf tdbg" width="40%"> &nbsp;Account name</td><td class="smallf"> &nbsp;{{bank_account_name}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Bank</td><td class="smallf"> &nbsp;{{bank_name}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;BSB</td><td class="smallf"> &nbsp;{{bank_bsb}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Account number</td><td class="smallf"> &nbsp;{{bank_account_number}}</td></tr>
</table>
HTML);

        // ---- Student details ----
        $sections[] = $rt('Student details', <<<'HTML'
<p>Please carefully check all of the details below to make sure they are correct.</p>
<p><b>Student details</b></p>
<table cellspacing="0" cellpadding="4" border="1" width="700px">
<tr><td class="smallf tdbg" width="25%"> &nbsp;First Name</td><td class="smallf" width="25%"> &nbsp;{{student_first_name}}</td><td class="smallf tdbg" width="25%"> &nbsp;Surname</td><td class="smallf" width="25%"> &nbsp;{{student_last_name}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Date of birth</td><td class="smallf"> &nbsp;{{student_date_of_birth}}</td><td class="smallf tdbg"> &nbsp;Gender</td><td class="smallf"> &nbsp;{{student_gender}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Nationality</td><td class="smallf"> &nbsp;{{student_nationality}}</td><td class="smallf tdbg"> &nbsp;Phone</td><td class="smallf"> &nbsp;{{student_phone}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Email</td><td class="smallf" colspan="3"> &nbsp;{{student_email}}</td></tr>
</table>
HTML);

        // ---- Course details ----
        $sections[] = $rt('Course details', <<<'HTML'
<p><b>Course details</b></p>
<table cellspacing="0" cellpadding="4" border="1" width="700px">
<tr><td class="smallf tdbg" width="40%"> &nbsp;Course code and title</td><td class="smallf"> &nbsp;{{course_code}} {{course_name}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Study mode, including any workplace placement required</td><td class="smallf"> &nbsp;{{study_mode}}, {{work_placement}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Study location</td><td class="smallf"> &nbsp;{{study_location}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Entry requirements</td><td class="smallf"> &nbsp;{{entry_requirements}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Conditions (if applicable)</td><td class="smallf"> &nbsp;{{condition_description}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Start date</td><td class="smallf"> &nbsp;{{course_start_date}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;End date</td><td class="smallf"> &nbsp;{{course_end_date}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Total duration</td><td class="smallf"> &nbsp;{{course_duration}} Weeks</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Hours per week</td><td class="smallf"> &nbsp;{{hours_per_week}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Holiday breaks</td><td class="smallf"> &nbsp;{{holiday_breaks}}</td></tr>
</table>
HTML);

        // ---- Enrolled courses (dynamic) ----
        $sections[] = $rt('Enrolled Courses Heading', '<p><b>Enrolled Courses</b></p>');
        $sections[] = $block('course_table', 'Courses');

        // ---- Course subjects (dynamic) ----
        $sections[] = $rt('Course Subjects Heading', '<p><b>Course Subjects</b></p>');
        $sections[] = $block('course_subjects', 'Course Subjects');

        // ---- Course fees due (dynamic) ----
        $sections[] = $rt('Course fees due Heading', '<p><b>Course fees due</b></p>');
        $sections[] = $block('fees_due', 'Course Fees Due');

        // ---- Payment schedule (dynamic) ----
        $sections[] = $rt('Payment schedule Heading', '<p><b>Payment schedule</b></p>');
        $sections[] = $block('payment_plan', 'Payment Plan');

        $sections[] = $break();

        // ---- Student Agreement: Fees and Refunds ----
        $sections[] = $rt('Fees and Refunds', <<<'HTML'
<p style="font-size:15px;"><b>Student Agreement</b></p>
<p><b>Information and terms and conditions</b></p>
<p><b>Fees and Refunds</b></p>
<p>We want to make sure you understand all fees and charges associated with your course so please carefully read this section before signing the Student Agreement.</p>
<p>Any fees and charges documented in the agreement will not change during the duration of your course.</p>
<p>We protect your fees at all times by:</p>
<ul>
<li>maintaining a sufficient amount in our account so that we are able to repay all tuition fees already paid</li>
<li>not requiring you to pay any more than $1,500 in one instalment for services to be provided.</li>
</ul>
<p>Please note that the following fees can apply in addition to the fees advertised in the Course Brochure. Additional fees that may apply in addition to tuition and non-tuition fees include:</p>
<table cellspacing="0" cellpadding="4" border="1" width="700px">
<tr><td class="smallf tdbg" width="60%"> &nbsp;<b>Additional fees that may apply</b></td><td class="smallf tdbg"> &nbsp;<b>Amount</b></td></tr>
<tr><td class="smallf"> &nbsp;Deferral fee</td><td class="smallf"> &nbsp;Nil</td></tr>
<tr><td class="smallf"> &nbsp;Re-assessment fee (students have a total of 2 attempts and any attempt thereafter will incur the stated fee)</td><td class="smallf"> &nbsp;$100</td></tr>
<tr><td class="smallf"> &nbsp;Fees for late payment of course fees</td><td class="smallf"> &nbsp;$100 per week for each week the payment for course fees is delayed</td></tr>
<tr><td class="smallf"> &nbsp;Credit transfer</td><td class="smallf"> &nbsp;Nil</td></tr>
<tr><td class="smallf"> &nbsp;RPL</td><td class="smallf"> &nbsp;Application fee of $250, Unit fee $500</td></tr>
<tr><td class="smallf"> &nbsp;Re-issuance of certificate</td><td class="smallf"> &nbsp;$100</td></tr>
</table>
<p>If these fees apply, they will be included in the amounts shown on the previous page. You are required to pay all fees and charges by the date indicated on the invoice. Where you are unable to make a payment by the specified date, please contact us to discuss alternative arrangements.</p>
<p>All payments are to be made by bank transfer into the account specified on the invoice.</p>
<p>Where fees are overdue and you have not made alternative arrangements, a first warning, second warning and notice of cancellation regarding non-payment of fees will be sent to you as follows:</p>
<ul>
<li>First warning letter: failing to pay an invoice within 5 days of receipt or contacting us to make alternative arrangements.</li>
<li>Second warning letter: failing to pay an invoice within 5 days of receipt of the first warning letter or contacting us to make alternative arrangements.</li>
<li>Notice of cancellation: failing to pay an invoice within 5 days of receipt of the second warning letter or contacting us to make alternative arrangements.</li>
</ul>
<p>Following cancellation of enrolment due to non-payment of fees, your debt will be referred to a debt collection agency.</p>
HTML);

        // ---- Refunds ----
        $sections[] = $rt('Refunds', <<<'HTML'
<p><b>Refunds</b></p>
<p>Please carefully read the following information about refunds. This applies whether you paid the tuition and non-tuition fees or if someone else paid them on your behalf.</p>
<p>All application fees are non-refundable except where we cancel a course before it has started.</p>
<p>If we cancel a course either before or after it starts, you will receive an automatic refund and do not need to complete the Refund Application Form. The refund will be provided within 10 working days of the default.</p>
<p>In all other circumstances, you should complete and submit a Refund Application Form which can be accessed from our office. This form must be submitted within 10 working days of the event that led to the request for the refund. The outcome of the refund assessment will be forwarded to you within 20 working days, as well as any applicable refund.</p>
<p>Refunds will be paid to you or to the person or organisation who paid the course fees and will be paid in Australian Dollars.</p>
<p>The refund policy does not remove your right to take further action under Australian Consumer Law.</p>
<p>In addition to the above circumstances, refunds apply as follows:</p>
<table cellspacing="0" cellpadding="4" border="1" width="700px">
<tr><td class="smallf tdbg" width="55%"> &nbsp;<b>Circumstance</b></td><td class="smallf tdbg"> &nbsp;<b>Refund due</b></td></tr>
<tr><td class="smallf"> &nbsp;{{organisation_name}} cancels course before commencement due to insufficient numbers or other unforeseen circumstances, including a sanction being imposed on {{organisation_name}} (known as provider default).</td><td class="smallf"> &nbsp;Full refund of all fees.</td></tr>
<tr><td class="smallf"> &nbsp;Student withdraws up to 4 weeks prior to course commencement.</td><td class="smallf"> &nbsp;Application fee not refunded. Refund of all other fees and charges.</td></tr>
<tr><td class="smallf"> &nbsp;Student withdraws less than 4 weeks prior to course commencement.</td><td class="smallf"> &nbsp;Application fee not refunded. Refund of 90% of all other fees and charges.</td></tr>
<tr><td class="smallf"> &nbsp;Student withdraws after commencement.</td><td class="smallf"> &nbsp;No refund. Fees for full study period (term) to be paid.</td></tr>
<tr><td class="smallf"> &nbsp;Student's enrolment is cancelled due to disciplinary action.</td><td class="smallf"> &nbsp;No refund. Fees for full study period (term) to be paid.</td></tr>
<tr><td class="smallf"> &nbsp;The student has supplied incorrect or incomplete information causing {{organisation_name}} to withdraw the offer of the course prior to commencement.</td><td class="smallf"> &nbsp;No refund. Fees for full study period (term) to be paid.</td></tr>
<tr><td class="smallf"> &nbsp;{{organisation_name}} cancels course due to unforeseen circumstances, including a sanction being imposed on {{organisation_name}} (known as provider default).</td><td class="smallf"> &nbsp;Application fee not refunded. Full refund of all unspent fees calculated as the weekly tuition fee multiplied by the weeks in the default period (calculated from the date of default).</td></tr>
</table>
HTML);

        // ---- Complaints and Appeals ----
        $sections[] = $rt('Complaints and Appeals', <<<'HTML'
<p><b>Complaints and Appeals</b></p>
<p>We sincerely hope not, but from time to time you may be unhappy with the services we provide or want to appeal a decision we have made. We take your complaints and appeals seriously and will ensure in assessing them that we look at the causes and action that we can take to ensure it does not happen again / reduce the likelihood of it happening again.</p>
<p>Complaints can be made against us as the RTO, our trainers and assessors and other staff, another learner of {{organisation_name}} as well as any third party that provides services on our behalf. Complaints can be in relation to any aspect of our services.</p>
<p>Appeals can be made in respect of any decision made by {{organisation_name}}. An appeal is a request for {{organisation_name}}'s decision to be reviewed in relation to a matter, including assessment appeals.</p>
<p>In managing complaints, we will ensure that the principles of natural justice and procedural fairness are adopted at every stage of the complaint process. Our internal complaints and appeals process can be accessed at no cost.</p>
<p>We do encourage you to firstly seek to address the issue informally by discussing it with the person involved. However, if you do not feel comfortable with this or you have tried this and did not get the outcome you wished, you can access the formal complaints and appeals process.</p>
<p>If you want to make a complaint or appeal, you must:</p>
<ul>
<li>submit your complaint or appeal in writing using the complaints and appeals form, which can be accessed from reception</li>
<li>submit your complaint within 30 calendar days of the incident or, in the case of an appeal, within 30 calendar days of the decision being made.</li>
</ul>
<p>We will acknowledge your complaint or appeal in writing within 3 working days of receipt and commence reviewing it within 5 working days. Complaints and appeals will be finalised as soon as practicable or within 30 calendar days. Where the matter is expected to take more than 60 calendar days, {{organisation_name}} will write to inform you of the reasons and provide regular updates of progress.</p>
<p>For assessment appeals, we will appoint an independent assessor to conduct a review of the assessment decision being appealed. We will communicate the result of the complaints and appeals process to you in writing, including the reasons for the decision.</p>
<p><b>Independent parties</b></p>
<p>Where the internal process has failed to resolve the complaint or appeal, you will be able to take it to an external mediation agency. {{organisation_name}} recommends the Resolution Institute. You will be responsible for all associated costs unless {{organisation_name}} initiates the external mediation process.</p>
<p>Complaints can also be made to the organisations indicated below:</p>
<p><b>National Training Complaints Hotline</b><br>Phone: 13 38 73, Monday–Friday, 8am to 6pm nationally<br>Online Complaints Form: https://www.dewr.gov.au/national-training-complaints-hotline/national-training-complaints-hotline-complaints-form</p>
<p><b>Australian Skills Quality Authority (ASQA)</b><br>More information can be found at https://www.asqa.gov.au/complaints</p>
<p>Nothing in this policy and procedure limits the rights of an individual to take action under Australia's Consumer Protection laws.</p>
HTML);

        // ---- Privacy Notice ----
        $sections[] = $rt('Privacy Notice', <<<'HTML'
<p><b>Privacy Notice</b></p>
<p><b>Why we collect your personal information</b></p>
<p>As a registered training organisation (RTO), we collect your personal information so we can process and manage your enrolment in a vocational education and training (VET) course with us. If you do not provide this information, we will be unable to process your enrolment.</p>
<p><b>How we use your personal information</b></p>
<p>We use your personal information to enable us to deliver VET courses to you, and otherwise, as needed, to comply with our obligations as an RTO.</p>
<p><b>How we disclose your personal information</b></p>
<p>We are required by law (under the National Vocational Education and Training Regulator Act 2011 (Cth) (NVETR Act)) to disclose the personal information we collect about you to the National VET Data Collection kept by the National Centre for Vocational Education Research Ltd (NCVER). We are also authorised by law to disclose your personal information to the relevant state or territory training authority.</p>
<p>The NCVER will collect, hold, use and disclose your personal information in accordance with the law, including the Privacy Act 1988 (Cth) and the NVETR Act. For more information about how the NCVER will handle your personal information please refer to the NCVER's Privacy Policy at www.ncver.edu.au/privacy.</p>
<p><b>Surveys</b></p>
<p>You may receive a student survey which may be run by a government department or an NCVER employee, agent, third-party contractor or another authorised agency. You may opt out of the survey at the time of being contacted.</p>
<p><b>Contact information</b></p>
<p>At any time, you may contact {{organisation_name}} to request access to or correct your personal information, make a complaint about how it has been handled, or ask a question about this Privacy Notice.</p>
<p>Our contact details are:<br>Email: {{organisation_email}}<br>Phone: {{organisation_phone}}</p>
<p><b>Requirement to provide change of contact details</b></p>
<p>You are required to notify us of any change to your contact details including your residential address, mobile number, email address and emergency contact within 7 days of the change occurring.</p>
HTML);

        // ---- Student declaration ----
        $sections[] = $rt('Student declaration', <<<'HTML'
<p><b>Student declaration</b></p>
<p>This document sets out the agreement between you and {{organisation_name}}. This Written Agreement, and the right to make complaints and seek appeals of decisions and action under various processes, does not affect the rights of the student to take action under the Australian Consumer Law if it applies.</p>
<p>By signing the declaration below, you are agreeing to this Student Agreement and all of the associated terms and conditions included with this Student Agreement.</p>
<p>You must keep a copy of this Student Agreement and payment receipts for all tuition and non-tuition fees. We will also keep a record for at least 2 years after you have completed or withdrawn from your course.</p>
<p>I confirm that the details in this Student Agreement are correct and that I accept all terms and conditions documented in this agreement.</p>
<table cellspacing="0" cellpadding="6" border="1" width="700px">
<tr><td class="smallf tdbg" width="30%"> &nbsp;Student name</td><td class="smallf"> &nbsp;{{student_name}}</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Signature</td><td class="smallf"> &nbsp;</td></tr>
<tr><td class="smallf tdbg"> &nbsp;Date</td><td class="smallf"> &nbsp;</td></tr>
</table>
HTML);

        StudentOfferTemplate::updateOrCreate(
            ['name' => 'Default Offer Letter'],
            ['layout' => json_encode($sections), 'status' => 1]
        );
    }
}
