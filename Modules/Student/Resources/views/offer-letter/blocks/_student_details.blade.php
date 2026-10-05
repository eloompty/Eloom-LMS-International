{{-- Dynamic block: applicant / offer details --}}
<table cellspacing="0" cellpadding="1" border="1" width="700px">
    <tr>
        <td class="smallf" width="50%"> &nbsp;RTO Legal Name:</td>
        <td class="smallff"> &nbsp;<b>{{ $company->company_name }}</b></td>
    </tr>
    <tr>
        <td class="smallf" width="50%"> &nbsp;RTO Name:</td>
        <td class="smallff"> &nbsp;<b>{{ $company->company_name }}</b></td>
    </tr>
    <tr>
        <td class="smallf" width="50%"> &nbsp;RTO Number:</td>
        <td class="smallff"> &nbsp;{{ $company->rto_no }}</td>
    </tr>
    <tr>
        <td class="smallf" width="50%"> &nbsp;CRICOS Provider Number:</td>
        <td class="smallff"> &nbsp;{{ $company->rto_no }}</td>
    </tr>
    <tr>
        <td class="smallf" width="50%"> &nbsp;CRICOS Course CODE:</td>
        <td class="smallff"> &nbsp;{{ implode(',', $cricos) }}</td>
    </tr>
    <tr>
        <td class="smallf" width="50%"> &nbsp;Student Name:</td>
        <td class="smallff"> &nbsp;{{ userName('Student', $letter->student_id) }}</td>
    </tr>
    <tr>
        <td class="smallf" width="50%"> &nbsp;Passport Number:</td>
        <td class="smallff"> &nbsp;{{ $letter->student->passport_no }}</td>
    </tr>
    <tr>
        <td class="smallf" width="50%"> &nbsp;Offer Number:</td>
        <td class="smallff"> &nbsp;{{ offerNumber($letter->id) }}</td>
    </tr>
    <tr>
        <td class="smallf" width="50%"> &nbsp;Address:</td>
        <td class="smallff">
            &nbsp;@if (optional($student_fees->first())->type == 'Onshore'){{ fullAddress('Student', $letter->student_id) }}@else{{ $letter->student->overseas_address }}@endif
        </td>
    </tr>
    <tr>
        <td class="smallf" width="50%"> &nbsp;Date of Birth:</td>
        <td class="smallff"> &nbsp;{{ dateFormat($letter->student->date_of_birth) }}</td>
    </tr>
</table>
