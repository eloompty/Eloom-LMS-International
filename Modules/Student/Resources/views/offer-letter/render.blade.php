<!DOCTYPE html>
<html>

<head>
    <title>Student Offer</title>
    <style>
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
        }

        .page-header {
            position: fixed;
            top: 0mm;
            width: 100%;
        }

        .page {
            page-break-after: always;
        }

        .smallf {
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
        }

        .smallff {
            font-family: "Times New Roman", Times, serif;
            font-size: 10px;
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
    </div>

    <div class="page-footer">
        <table width="700px">
            <tr>
                <td class="smallff" colspan="2" align="center">
                    {{ $company->company_name }}, RTO Code {{ $company->rto_no }} CRICOS Provider Code {{ $company->rto_no }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <td>
                    <div class="page-header-space"></div>
                </td>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    {!! $body !!}
                </td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <td>
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
