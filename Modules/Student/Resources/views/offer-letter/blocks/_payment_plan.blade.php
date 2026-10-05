{{-- Dynamic block: payment schedule (sample layout) --}}
<table cellspacing="0" cellpadding="4" border="1" width="700px">
    @forelse($payment_plan as $i => $p)
    <tr>
        <td class="smallf tdbg" width="30%"> &nbsp;<b>Fee instalment {{ $i + 1 }}</b></td>
        <td class="smallf" width="20%"> &nbsp;${{ $p['amount'] }}</td>
        <td class="smallf tdbg" width="20%"> &nbsp;<b>Due date</b></td>
        <td class="smallf" width="30%"> &nbsp;@if($p['due_date']){{ dateFormat($p['due_date']) }}@else - @endif</td>
    </tr>
    @empty
    <tr>
        <td class="smallf" colspan="4"> &nbsp;No payment instalments found.</td>
    </tr>
    @endforelse
</table>
