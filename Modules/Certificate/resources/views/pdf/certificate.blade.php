<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    <style>
        @php
            $bg           = $layout['background_color'] ?? '#ffffff';
            $textColor    = $layout['text_color'] ?? '#333333';
            $fontSize     = (int)($layout['font_size'] ?? 14);
            $borderColor  = $layout['border_color'] ?? '#b8860b';
            $borderStyle  = $layout['border_style'] ?? 'double';
            $borderWidth  = (int)($layout['border_width'] ?? 6);
            $titleColor   = $layout['title_color'] ?? '#2c3e50';
            $titleSize    = (int)($layout['title_size'] ?? 32);
            $subtitleSize = (int)($layout['subtitle_size'] ?? 14);
            $bodySize     = (int)($layout['body_size'] ?? 18);
            $bodyColor    = $layout['body_color'] ?? '#333333';
            $footerColor  = $layout['footer_color'] ?? '#888888';
        @endphp
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background-color: {{ $bg }};
            color: {{ $textColor }};
            font-size: {{ $fontSize }}px;
            margin: 0;
            padding: 28px;
        }
        .cert-wrapper {
            border: {{ $borderWidth }}px {{ $borderStyle }} {{ $borderColor }};
            padding: 44px 64px;
            text-align: center;
            min-height: 490px;
            background-color: {{ $bg }};
        }
        .cert-logo { margin-bottom: 8px; }
        .cert-subtitle {
            font-size: {{ $subtitleSize }}px;
            color: #555555;
            margin: 0 0 6px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        h1.cert-title {
            font-size: {{ $titleSize }}px;
            color: {{ $titleColor }};
            margin: 8px 0 20px;
            font-weight: bold;
        }
        .cert-body {
            font-size: {{ $bodySize }}px;
            line-height: 1.9;
            margin: 16px 0 24px;
            color: {{ $bodyColor }};
        }
        .cert-signature-area { margin-top: 44px; display: flex; justify-content: center; }
        .cert-signature-img { margin-bottom: 4px; }
        .cert-signature {
            display: inline-block;
            border-top: 1px solid #aaaaaa;
            padding-top: 6px;
            min-width: 220px;
            font-size: 12px;
            color: #555555;
        }
        .cert-footer {
            font-size: 10px;
            color: {{ $footerColor }};
            margin-top: 28px;
            border-top: 1px solid #eeeeee;
            padding-top: 8px;
        }
        .cert-id { font-size: 9px; color: #bbbbbb; margin-top: 6px; }
    </style>
</head>
<body>
    <div class="cert-wrapper">

        @php
            $logoPath = !empty($layout['logo_path'])
                ? public_path($layout['logo_path'])
                : (getSettingValue('logo') ? public_path(getSettingValue('logo')) : null);
        @endphp
        @if(!empty($layout['show_logo']) && $logoPath && file_exists($logoPath))
        <div class="cert-logo">
            <img src="{{ $logoPath }}" height="60" alt="Logo">
        </div>
        @endif

        @if(!empty($layout['subtitle_text']))
        <p class="cert-subtitle">
            {!! \Modules\Certificate\Services\CertificateService::mergeText($layout['subtitle_text'], $mergeFields) !!}
        </p>
        @endif

        <h1 class="cert-title">
            {!! \Modules\Certificate\Services\CertificateService::mergeText($layout['title_text'] ?? 'Certificate', $mergeFields) !!}
        </h1>

        <div class="cert-body">
            {!! nl2br(\Modules\Certificate\Services\CertificateService::mergeText($layout['body_text'] ?? '', $mergeFields)) !!}
        </div>

        @if(!empty($layout['show_signature']))
        @php
            $signaturePath = !empty($layout['signature_path']) ? public_path($layout['signature_path']) : null;
        @endphp
        <div class="cert-signature-area">
            <div style="text-align:center;">
                @if($signaturePath && file_exists($signaturePath))
                <div class="cert-signature-img">
                    <img src="{{ $signaturePath }}" height="48" alt="Signature">
                </div>
                @endif
                <div class="cert-signature">
                    {{ $layout['signature_label'] ?? 'Director' }}
                </div>
            </div>
        </div>
        @endif

        @if(!empty($layout['footer_text']))
        <div class="cert-footer">
            {!! \Modules\Certificate\Services\CertificateService::mergeText($layout['footer_text'], $mergeFields) !!}
        </div>
        @endif

        <div class="cert-id">Certificate ID: {{ $cert->uuid }}</div>
    </div>
</body>
</html>
