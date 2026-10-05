{{-- Dynamic block: first payment + payment schedule --}}
<div id="center">
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
            <td class="smallf" width="80"> &nbsp;Due on acceptance of offer</td>
            <td class="smallf" width="100">
                Total: $ {{ $first_installment }};
                @foreach ($total_first_installments as $fi) {{ $fi }} @endforeach
            </td>
            <td class="smallf" width="125"> &nbsp;${{ $total_enrollment + $total_material_fee }}</td>
            <td class="smallf" width="90"> &nbsp;{{ $total_enrollment + $total_fee + $total_material_fee }}</td>
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
            <td class="smallf" width="65"> &nbsp;$0</td>
            <td class="smallf" width="30"> &nbsp;$0</td>
            <td class="smallf" width="80"> &nbsp;${{ $value->amount }}</td>
        </tr>
        @php $index++ @endphp
        @endforeach
        @endforeach
    </table>
</div>
