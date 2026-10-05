<?php

namespace Modules\Certificate\Http\Controllers\Student;

use Illuminate\Routing\Controller;
use Modules\Certificate\Entities\IssuedCertificate;
use Modules\Certificate\Services\CertificateService;

class StudentCertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    public function index()
    {
        $student      = auth('student')->user();
        $certificates = IssuedCertificate::with('template')
            ->where('student_id', $student->id)
            ->where('status', 1)
            ->where('revoked', false)
            ->orderByDesc('issued_date')
            ->get();

        return view('certificate::student.index', compact('certificates'));
    }

    public function download($uuid)
    {
        $student = auth('student')->user();
        $cert    = IssuedCertificate::with('template', 'student', 'intakeCourse')
            ->where('uuid', $uuid)
            ->where('student_id', $student->id)
            ->where('revoked', false)
            ->firstOrFail();

        $path = storage_path("app/public/{$cert->file_path}");

        if (!file_exists($path)) {
            $pdf = (new CertificateService)->renderPdf($cert, $cert->template, $cert->student, $cert->student_intake_course_id);
            return response($pdf, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"certificate_{$cert->uuid}.pdf\"",
            ]);
        }

        return response()->download($path, "certificate_{$cert->uuid}.pdf");
    }
}
