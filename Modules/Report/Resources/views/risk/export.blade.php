<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Risk Analysis Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; font-size: 12px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #343a40; color: white; padding: 8px 6px; text-align: left; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #dee2e6; font-size: 10px; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .badge { padding: 3px 8px; border-radius: 3px; color: white; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ff851b; color: #212529; }
        .badge-info { background-color: #ffc107; color: #212529; }
        .badge-success { background-color: #28a745; }
        .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <h1>Student Risk Analysis Report</h1>
    <p class="subtitle">Generated on {{ date('d/m/Y H:i') }} | Total Records: {{ $scores->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>ID No</th>
                <th>Course</th>
                <th>Attendance</th>
                <th>Assignments</th>
                <th>Grades</th>
                <th>Fees</th>
                <th>Engagement</th>
                <th>Overall</th>
                <th>Risk Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scores as $index => $score)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ userName('Student', $score->student_id) }}</td>
                <td>{{ optional($score->student)->id_no ?? '-' }}</td>
                <td>{{ optional(optional(optional($score->studentIntakeCourse)->intakeCourse)->course)->course_name ?? '-' }}</td>
                <td>{{ $score->attendance_score }}</td>
                <td>{{ $score->assignment_score }}</td>
                <td>{{ $score->grade_score }}</td>
                <td>{{ $score->fee_score }}</td>
                <td>{{ $score->engagement_score }}</td>
                <td><strong>{{ $score->overall_score }}</strong></td>
                <td>
                    @php
                        $badgeColors = ['critical' => 'danger', 'high' => 'warning', 'medium' => 'info', 'low' => 'success'];
                    @endphp
                    <span class="badge badge-{{ $badgeColors[$score->risk_level] ?? 'secondary' }}">
                        {{ ucfirst($score->risk_level) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ getTitle() }} &mdash; Student Risk Analysis Report &mdash; {{ date('Y') }}
    </div>
</body>
</html>
