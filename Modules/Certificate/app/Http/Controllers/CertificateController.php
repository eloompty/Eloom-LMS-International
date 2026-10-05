<?php

namespace Modules\Certificate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Certificate\Entities\CertificateTemplate;
use Modules\Certificate\Entities\IssuedCertificate;
use Modules\Certificate\Services\CertificateService;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user', ['except' => ['verify']]);
    }

    // ── Template management ──────────────────────────────────────────────────

    public function templateIndex()
    {
        $templates = CertificateTemplate::where('status', 1)->orderByDesc('id')->get();
        return view('certificate::admin.template.index', compact('templates'));
    }

    public function templateCreate()
    {
        return view('certificate::admin.template.create');
    }

    public function templateStore(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:completion,diploma,degree,transcript,badge',
            'layout'    => 'required|json',
            'logo'      => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:2048',
        ]);

        $layout = json_decode($request->layout, true);
        if ($request->hasFile('logo')) {
            $layout['logo_path'] = $this->storeImage($request->file('logo'), 'certificate_logos');
        }
        if ($request->hasFile('signature')) {
            $layout['signature_path'] = $this->storeImage($request->file('signature'), 'certificate_signatures');
        }

        CertificateTemplate::create([
            'name'       => $request->name,
            'type'       => $request->type,
            'layout'     => $layout,
            'is_default' => $request->boolean('is_default'),
            'status'     => 1,
        ]);

        activityLog('Admin', "Certificate template created: {$request->name}");
        return redirect()->route('admin.certificate.template.index')->with('success', 'Template created successfully');
    }

    public function templateEdit($id)
    {
        $template = CertificateTemplate::findOrFail($id);
        return view('certificate::admin.template.edit', compact('template'));
    }

    public function templateUpdate(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:completion,diploma,degree,transcript,badge',
            'layout'    => 'required|json',
            'logo'      => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:2048',
        ]);

        $template = CertificateTemplate::findOrFail($id);

        $layout   = json_decode($request->layout, true);
        $existing = $template->layout ?? [];
        if ($request->hasFile('logo')) {
            $layout['logo_path'] = $this->storeImage($request->file('logo'), 'certificate_logos');
        } elseif (!empty($existing['logo_path'])) {
            $layout['logo_path'] = $existing['logo_path'];
        }
        if ($request->hasFile('signature')) {
            $layout['signature_path'] = $this->storeImage($request->file('signature'), 'certificate_signatures');
        } elseif (!empty($existing['signature_path'])) {
            $layout['signature_path'] = $existing['signature_path'];
        }

        $template->update([
            'name'       => $request->name,
            'type'       => $request->type,
            'layout'     => $layout,
            'is_default' => $request->boolean('is_default'),
        ]);

        activityLog('Admin', "Certificate template_id: {$id} updated");
        return redirect()->route('admin.certificate.template.index')->with('success', 'Template updated');
    }

    public function templateDestroy($id)
    {
        CertificateTemplate::findOrFail($id)->update(['status' => 2]);
        activityLog('Admin', "Certificate template_id: {$id} deleted");
        return redirect()->back()->with('success', 'Template deleted');
    }

    /**
     * Store an uploaded certificate image and return its public-relative path.
     */
    private function storeImage($file, string $subdir): string
    {
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path("images/{$subdir}"), $imageName);
        return "images/{$subdir}/" . $imageName;
    }

    // ── Issuance ─────────────────────────────────────────────────────────────

    public function issuedIndex()
    {
        $certificates = IssuedCertificate::with('student', 'template')
            ->where('status', 1)
            ->orderByDesc('id')
            ->paginate(30);
        return view('certificate::admin.issued.index', compact('certificates'));
    }

    public function issueForm()
    {
        $templates = CertificateTemplate::where('status', 1)->get();
        $students  = Student::where('status', 1)->orderBy('first_name')->get();
        $studentIds = $students->pluck('id')->toArray();
        $studentIntakeCourses = StudentIntakeCourse::with('intakeCourse.course', 'intakeCourse.intake')
            ->whereIn('student_id', $studentIds)
            ->where('is_enrolled', 1)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        $studentCourses = [];
        foreach ($studentIntakeCourses as $studentIntakeCourse) {
            $intakeCourse = $studentIntakeCourse->intakeCourse;
            $courseName = $intakeCourse && $intakeCourse->course ? $intakeCourse->course->course_name : 'Course';
            $intakeName = $intakeCourse && $intakeCourse->intake ? $intakeCourse->intake->name : null;
            $referenceName = $intakeCourse ? $intakeCourse->reference_name : null;
            $label = $courseName;
            if ($referenceName) {
                $label .= ' - ' . $referenceName;
            }
            if ($intakeName) {
                $label .= ' (' . $intakeName . ')';
            }
            $label .= ' [ID: ' . $studentIntakeCourse->id . ']';

            $studentCourses[$studentIntakeCourse->student_id][] = [
                'id' => $studentIntakeCourse->id,
                'text' => $label,
            ];
        }

        return view('certificate::admin.issued.create', compact('templates', 'students', 'studentCourses'));
    }

    public function issue(Request $request)
    {
        $request->validate([
            'student_id'               => 'required|exists:students,id',
            'certificate_template_id'  => 'required|exists:certificate_templates,id',
            'student_intake_course_id' => 'nullable|exists:student_intake_courses,id',
        ]);

        if ($request->filled('student_intake_course_id')) {
            $studentIntakeCourse = StudentIntakeCourse::where('id', $request->student_intake_course_id)
                ->where('student_id', $request->student_id)
                ->where('is_enrolled', 1)
                ->where('status', 1)
                ->first();

            if (!$studentIntakeCourse) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['student_intake_course_id' => 'Please select an enrolled intake course for the selected student.']);
            }
        }

        $cert = (new CertificateService)->issue(
            $request->student_id,
            $request->certificate_template_id,
            'manual',
            $request->student_intake_course_id,
            null,
            auth('user')->id()
        );

        activityLog('Admin', "Certificate issued to student_id: {$request->student_id}, cert uuid: {$cert->uuid}");
        return redirect()->route('admin.certificate.issued.index')->with('success', 'Certificate issued successfully');
    }

    public function bulkIssue(Request $request)
    {
        $request->validate([
            'student_intake_course_ids' => 'required|array',
            'certificate_template_id'   => 'required|exists:certificate_templates,id',
        ]);

        $count = (new CertificateService)->bulkIssue(
            $request->student_intake_course_ids,
            $request->certificate_template_id,
            auth('user')->id()
        );

        activityLog('Admin', "Bulk certificates issued: {$count} certificates");
        return redirect()->route('admin.certificate.issued.index')->with('success', "{$count} certificate(s) issued");
    }

    public function revoke(Request $request, $id)
    {
        $cert = IssuedCertificate::findOrFail($id);
        $cert->update(['revoked' => true, 'revoke_reason' => $request->reason]);
        activityLog('Admin', "Certificate uuid: {$cert->uuid} revoked");
        return redirect()->back()->with('success', 'Certificate revoked');
    }

    public function download($id)
    {
        $cert = IssuedCertificate::with('template', 'student', 'intakeCourse')->findOrFail($id);
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

    // ── Public verification (no auth) ─────────────────────────────────────────

    public function verify($uuid)
    {
        $cert = IssuedCertificate::with('student', 'template', 'intakeCourse.intakeCourse.course')
            ->where('uuid', $uuid)
            ->first();

        return view('certificate::verify', compact('cert'));
    }
}
