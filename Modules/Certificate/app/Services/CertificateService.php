<?php

namespace Modules\Certificate\Services;

use Illuminate\Support\Str;
use Modules\Certificate\Entities\CertificateTemplate;
use Modules\Certificate\Entities\IssuedCertificate;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Dompdf\Dompdf;
use Dompdf\Options;

class CertificateService
{
    /**
     * Issue a certificate for a student and store the PDF.
     * Returns the IssuedCertificate record.
     */
    public function issue(
        int $studentId,
        int $templateId,
        string $triggerType,
        ?int $studentIntakeCourseId = null,
        ?int $studentIntakeUnitId = null,
        ?int $issuedByUserId = null
    ): IssuedCertificate {
        $student  = Student::findOrFail($studentId);
        $template = CertificateTemplate::findOrFail($templateId);

        $uuid = Str::uuid()->toString();

        $cert = IssuedCertificate::create([
            'uuid'                       => $uuid,
            'certificate_template_id'    => $templateId,
            'student_id'                 => $studentId,
            'student_intake_course_id'   => $studentIntakeCourseId,
            'student_intake_unit_id'     => $studentIntakeUnitId,
            'trigger_type'               => $triggerType,
            'issued_date'                => now()->toDateString(),
            'issued_by_user_id'          => $issuedByUserId,
            'status'                     => 1,
        ]);

        $pdfContent = $this->renderPdf($cert, $template, $student, $studentIntakeCourseId);
        $path = "certificates/{$uuid}.pdf";
        \Storage::put("public/{$path}", $pdfContent);

        $cert->update(['file_path' => $path]);

        return $cert;
    }

    /**
     * Bulk issue one certificate per student in a StudentIntakeCourse list.
     */
    public function bulkIssue(array $studentIntakeCourseIds, int $templateId, ?int $issuedByUserId = null): int
    {
        $count = 0;
        foreach ($studentIntakeCourseIds as $sicId) {
            $sic = StudentIntakeCourse::find($sicId);
            if (!$sic) {
                continue;
            }
            $this->issue($sic->student_id, $templateId, 'course_completion', $sicId, null, $issuedByUserId);
            $count++;
        }
        return $count;
    }

    /**
     * Render the certificate PDF bytes using the template's JSON layout + student data.
     */
    public function renderPdf(IssuedCertificate $cert, CertificateTemplate $template, Student $student, ?int $studentIntakeCourseId): string
    {
        $layout = $template->layout ?? [];

        $courseName = null;
        $intakeName = null;
        if ($studentIntakeCourseId) {
            $sic        = StudentIntakeCourse::with('intakeCourse.course', 'intakeCourse.intake')->find($studentIntakeCourseId);
            $courseName = optional(optional($sic?->intakeCourse)->course)->course_name;
            $intakeName = optional(optional($sic?->intakeCourse)->intake)->name;
        }

        $mergeFields = [
            '{{student_name}}'   => trim("{$student->first_name} {$student->last_name}"),
            '{{course_name}}'    => $courseName ?? '',
            '{{intake_name}}'    => $intakeName ?? '',
            '{{issued_date}}'    => $cert->issued_date,
            '{{cert_id}}'        => $cert->uuid,
            '{{verify_url}}'     => $cert->verify_url,
        ];

        $html = view('certificate::pdf.certificate', [
            'layout'      => $layout,
            'mergeFields' => $mergeFields,
            'student'     => $student,
            'cert'        => $cert,
        ])->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Merge template fields into a text string.
     */
    public static function mergeText(string $text, array $mergeFields): string
    {
        return str_replace(array_keys($mergeFields), array_values($mergeFields), $text);
    }
}
