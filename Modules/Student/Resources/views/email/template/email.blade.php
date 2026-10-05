@extends('student::email.template.layout')
@section('title', 'Email')

@section('content')

<td width='100%'>
    <table bgcolor='#FFFFFF' border='0' cellspacing='0' style='padding-right:25px;' width='100%'>
        <tr>
            <td bgcolor='#FFFFFF' style='text-align:right;' width='100%'>
                <p><strong>Date :</strong> {{ date('d/m/Y') }} </p>
                <!-- <a href='#'><img alt='Main banner image and link' border='0' src='http://placehold.it/72x100' style='display:inline-block; max-width:72px !important; width:100% !important; height:auto !important;'></a> -->
            </td>
        </tr>
        <tr style="padding: 20px;">
            <td bgcolor='#FFFFFF' style='text-align:center;' width='100%'> <strong>Subject: </strong> {{ $subject }}</td>
        </tr>
    </table>
    <table bgcolor='#FFFFFF' border='0' cellpadding='25' cellspacing='0' width='100%'>
        <tr>
            <td bgcolor='#FFFFFF' style='text-align:left;' width='100%'>
                <p style='color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; padding:0; font-weight:normal;'>
                    Dear {{ $name }},
                    <br>
                    {{ $address }}
                </p>
                <p style='color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px;margin-left:20px; padding:0; font-weight:normal;'>
                    <?php echo $content; ?>
                </p>
                <table border='0' cellpadding='0' cellspacing='0' class='emailwrapto100pc' width='100%'>
                    <!-- <tr>
                        <td align='right' class='emailcolsplit' valign='top' width='100%'>
                            <p style='color:#222222; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:19px; margin-top:0; margin-bottom:20px; margin-left:20px; padding:0; font-weight:normal; text-align:left;'>
                                Please call us at {{ $company_phone }} with any questions.
                            </p>
                        </td>
                    </tr> -->
                    <tr>
                        <td align='left' class='emailcolsplit' valign='top' width='58%'>
                            <p style='font-size:15px; margin: 0; line-height: 1.25em;'>
                            <p>
                                <strong style='font-size: 20px'>Sincierly Yours</strong><br>
                                {{ $company_ceo }}<br>
                                {{ $company_address }}<br>
                                <!-- <abbr title='Phone'><strong>P:</strong></abbr>555.555.5555<br> -->
                                <!-- <strong>Email:</strong><a href='mailto:hr@yourcompany.com'>hr@yourcompany.com</a> -->
                            </p>
                            </p>
                        </td>
                    </tr>

                    <tr>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</td>

@endsection