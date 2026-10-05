<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate Verification</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 60px auto; padding: 20px; }
        .valid { border: 2px solid #28a745; padding: 20px; border-radius: 6px; }
        .invalid { border: 2px solid #dc3545; padding: 20px; border-radius: 6px; }
        h2 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td { padding: 6px 10px; border-bottom: 1px solid #eee; }
        td:first-child { font-weight: bold; width: 160px; }
    </style>
</head>
<body>
    @if($cert && !$cert->revoked)
    <div class="valid">
        <h2 style="color:#28a745">✓ Certificate is Valid</h2>
        <table>
            <tr><td>Student</td><td>{{ optional($cert->student)->first_name }} {{ optional($cert->student)->last_name }}</td></tr>
            <tr><td>Type</td><td>{{ ucfirst(optional($cert->template)->type) }}</td></tr>
            <tr><td>Template</td><td>{{ optional($cert->template)->name }}</td></tr>
            <tr><td>Course</td><td>{{ optional(optional(optional($cert->intakeCourse)?->intakeCourse)?->course)?->course_name ?? '—' }}</td></tr>
            <tr><td>Issued Date</td><td>{{ $cert->issued_date }}</td></tr>
            <tr><td>Certificate ID</td><td style="font-size:11px;">{{ $cert->uuid }}</td></tr>
        </table>
    </div>
    @elseif($cert && $cert->revoked)
    <div class="invalid">
        <h2 style="color:#dc3545">✗ Certificate Revoked</h2>
        <p>This certificate has been revoked by the issuing institution.</p>
        <p><strong>Reason:</strong> {{ $cert->revoke_reason ?? 'Not specified' }}</p>
    </div>
    @else
    <div class="invalid">
        <h2 style="color:#dc3545">✗ Certificate Not Found</h2>
        <p>No certificate was found for this verification link. It may be invalid or expired.</p>
    </div>
    @endif
    <p style="font-size:11px;color:#999;margin-top:30px;">Powered by Eloom LMS</p>
</body>
</html>
