{{-- Dynamic block: signature + company footer --}}
<table cellpadding="0" cellspacing="0" width="700px">
    <tr>
        <td class="smallf">
            @if (!empty($offerSignature))
            <img src="{{ !empty($isPdf) ? $offerSignature : asset($offerSignature) }}" height="25" alt="">
            @endif
            <br>
            {{ getSettingValue('offer_signed_by_name') }}<br>
            {{ getSettingValue('offer_signed_by_designation') }}<br>
            {{ $company->company_name }} <br><br>
            {{ $company->company_name }}, RTO Code {{ $company->rto_no }} CRICOS Provider Code {{ $company->rto_no }}
        </td>
    </tr>
</table>
