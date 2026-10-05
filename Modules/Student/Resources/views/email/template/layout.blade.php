<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        #outlook a {
            padding: 0
        }

        body {
            width: 100% !important;
            background-color: #333;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
            margin: 0 !important;
            padding: 0 !important
        }

        .ReadMsgBody {
            width: 100%
        }

        .ExternalClass {
            width: 100%
        }

        ol li {
            margin-bottom: 15px
        }

        img {
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none
        }

        #backgroundTable {
            height: 100% !important;
            margin: 0;
            padding: 0;
            width: 100% !important
        }

        p {
            margin: 1em 0
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #222 !important;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 100% !important
        }

        table td {
            border-collapse: collapse
        }

        .yshortcuts,
        .yshortcuts a,
        .yshortcuts a:link,
        .yshortcuts a:visited,
        .yshortcuts a:hover,
        .yshortcuts a span {
            color: #000;
            text-decoration: none !important;
            border-bottom: none !important;
            background: none !important
        }

        .im {
            color: #000
        }

        div[id='tablewrap'] {
            width: 100%;
            max-width: 600px !important
        }

        table[class='fulltable'],
        td[class='fulltd'] {
            max-width: 100% !important;
            width: 100% !important;
            height: auto !important
        }

        @media screen and (max-device-width: 430px),
        screen and (max-width: 430px) {
            td[class=emailcolsplit] {
                width: 100% !important;
                float: left !important;
                padding-left: 0 !important;
                max-width: 430px !important
            }

            td[class=emailcolsplit] img {
                margin-bottom: 20px !important
            }
        }
    </style>
</head>

<body style='width:100% !important; height: 100% !important; margin:0 !important; padding:0 !important; -webkit-text-size-adjust:none; -ms-text-size-adjust:none; background-color:#FFFFFF;'>
    <table border='0' cellpadding='0' align='center' cellspacing='0' id='backgroundTable' style='height: 100% !important; margin:0; padding:0; width:100% !important; background-color:#333; color:#222222;'>
        <tr>
            <td>
                <div id='tablewrap' align='center' style='width:100% !important; max-width:600px !important; text-align:center !important; margin-top:0 !important; margin-right: auto !important; margin-bottom:0 !important; margin-left: auto !important;'>
                    <table align='center' border='0' cellpadding='0' cellspacing='0' id='contenttable' style='background-color:#FFFFFF; text-align:center !important; margin-top:0 !important; margin-right: auto !important; margin-bottom:0 !important; margin-left: auto !important; border:none; width: 100% !important; max-width:600px !important;' width='600'>
                        <tr>
                            @yield('content')
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>