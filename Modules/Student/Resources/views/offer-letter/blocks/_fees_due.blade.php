{{-- Dynamic block: course fees due (sample layout) --}}
@php $grand_total = $total_enrollment + $total_fee + $total_material_fee; @endphp
<table cellspacing="0" cellpadding="4" border="1" width="700px">
    <tr>
        <td class="smallf tdbg" width="25%"> &nbsp;<b>Enrolment fee</b></td>
        <td class="smallf" width="25%"> &nbsp;${{ $total_enrollment }}</td>
        <td class="smallf tdbg" width="25%"> &nbsp;<b>Total tuition fee</b></td>
        <td class="smallf" width="25%"> &nbsp;${{ $total_fee }}</td>
    </tr>
    <tr>
        <td class="smallf tdbg"> &nbsp;<b>Additional fees if applicable</b></td>
        <td class="smallf"> &nbsp;${{ $total_material_fee }}</td>
        <td class="smallf tdbg"> &nbsp;<b>Total fees</b></td>
        <td class="smallf"> &nbsp;${{ $grand_total }}</td>
    </tr>
</table>
