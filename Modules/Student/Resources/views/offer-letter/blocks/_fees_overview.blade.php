{{-- Dynamic block: course fees overview + proforma student invoice summary --}}
<p class="smallf"><b>Course Fees Overview</b></p>
<table cellspacing="0" cellpadding="1" border="1" width="700px">
    @foreach($student_fees as $fee)
    <tr>
        <td class="smallf tdbg" width="50%"> &nbsp;Enrolment Application Fee (non-refundable)</td>
        <td class="smallff">$ {{ $fee->enrollment_fee }}</td>
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
    @endforeach
</table>
<p class="smallf">Proforma Student Invoice</p>
<div id="center">
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
            <td class="smallf" width="100"> &nbsp;${{ $total_material_fee }}</td>
            <td class="smallf" width="80"> &nbsp;$0.00</td>
            <td class="smallf" width="90"> &nbsp;${{ $total_enrollment + $total_fee + $total_material_fee }}</td>
        </tr>
    </table>
</div>
