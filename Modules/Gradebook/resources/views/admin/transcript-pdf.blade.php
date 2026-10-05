<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academic Transcript</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 20px; text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 13px; color: #666; margin-bottom: 20px; }
        .section-title { font-size: 14px; font-weight: bold; margin: 16px 0 6px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #f0f0f0; padding: 5px 8px; text-align: left; border: 1px solid #ccc; }
        td { padding: 4px 8px; border: 1px solid #ddd; }
        .summary { margin: 12px 0; padding: 10px; border: 1px solid #ccc; background: #fafafa; }
        .pass { color: #1a7a1a; font-weight: bold; }
        .fail { color: #cc0000; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 10px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <h1>Academic Transcript</h1>
    <div class="subtitle">UNOFFICIAL COPY — For verification contact your institution</div>

    <table>
        <tr>
            <td><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</td>
            <td><strong>Student ID:</strong> {{ $student->student_id ?? $student->id }}</td>
        </tr>
        <tr>
            <td><strong>Course:</strong> {{ optional(optional($intakeCourse->intakeCourse)->course)->course_name }}</td>
            <td><strong>Intake:</strong> {{ optional(optional($intakeCourse->intakeCourse)->intake)->name }}</td>
        </tr>
        <tr>
            <td><strong>Enrolment Start:</strong> {{ $intakeCourse->starting_date ?? '-' }}</td>
            <td><strong>Enrolment End:</strong> {{ $intakeCourse->ending_date ?? '-' }}</td>
        </tr>
    </table>

    @if(count($data['subjects']))
    <div class="section-title">Subject Results</div>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Full Marks</th>
                <th>Pass Marks</th>
                <th>Obtained</th>
                <th>%</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['subjects'] as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['full_marks'] }}</td>
                <td>{{ $row['pass_marks'] }}</td>
                <td>{{ $row['obtain_marks'] }}</td>
                <td>{{ $row['percentage'] !== null ? $row['percentage'].'%' : '-' }}</td>
                <td class="{{ $row['result'] === 'Pass' ? 'pass' : ($row['result'] === 'Fail' ? 'fail' : '') }}">
                    {{ $row['result'] }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(count($data['units']))
    <div class="section-title">Unit Results</div>
    <table>
        <thead>
            <tr>
                <th>Unit</th>
                <th>Full Marks</th>
                <th>Pass Marks</th>
                <th>Obtained</th>
                <th>%</th>
                <th>Result</th>
                <th>Outcome</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['units'] as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['full_marks'] }}</td>
                <td>{{ $row['pass_marks'] }}</td>
                <td>{{ $row['obtain_marks'] }}</td>
                <td>{{ $row['percentage'] !== null ? $row['percentage'].'%' : '-' }}</td>
                <td class="{{ $row['result'] === 'Pass' ? 'pass' : ($row['result'] === 'Fail' ? 'fail' : '') }}">
                    {{ $row['result'] }}
                </td>
                <td>{{ $row['outcome'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="summary">
        <strong>Overall Percentage:</strong> {{ $data['overall_pct'] !== null ? $data['overall_pct'].'%' : 'N/A' }}
        &nbsp;&nbsp;&nbsp;
        <strong>Attendance Rate:</strong> {{ $data['attendance_rate'] !== null ? $data['attendance_rate'].'%' : 'N/A' }}
        &nbsp;&nbsp;&nbsp;
        <strong>Academic Standing:</strong> {{ $data['standing'] }}
    </div>

    <div class="footer">
        Generated on {{ now()->format('d M Y, H:i') }} &nbsp;|&nbsp; This is an unofficial transcript.
    </div>
</body>
</html>
