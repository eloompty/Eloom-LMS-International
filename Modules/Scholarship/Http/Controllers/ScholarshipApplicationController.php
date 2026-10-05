<?php

namespace Modules\Scholarship\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Scholarship\Entities\Scholarship;
use Modules\Scholarship\Entities\ScholarshipApplication;
use Modules\Scholarship\Entities\FeeDiscount;
use Modules\Scholarship\Services\DisbursementService;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourseFee;

class ScholarshipApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index(Request $request)
    {
        if (checkRole('scholarship_application', 'view') != true) {
            return abort(404);
        }

        activityLog('Admin', 'Opened Scholarship Applications List');
        $applications = ScholarshipApplication::with(['scholarship', 'student', 'intakeCourse.course', 'disbursements'])
            ->when($request->status !== null && $request->status !== '', function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('scholarship::application.index', compact('applications'))->with('no', 1);
    }

    public function create($student_id, $fee_id)
    {
        if (checkRole('scholarship_application', 'add') != true) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'You do not have permission to apply scholarships');
        }

        $student = Student::findOrFail($student_id);
        $fee = StudentIntakeCourseFee::with('intakeCourse.course')->findOrFail($fee_id);
        $scholarships = Scholarship::active()->orderBy('name')->get();

        activityLog('Admin', 'Opened scholarship application create for student_id: ' . $student_id);
        return view('scholarship::application.create', compact('student', 'fee', 'scholarships'));
    }

    public function store(Request $request)
    {
        if (checkRole('scholarship_application', 'add') != true) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'You do not have permission to apply scholarships');
        }

        $fee = StudentIntakeCourseFee::findOrFail($request->student_intake_course_fee_id);

        // Multiple scholarships may stack on one fee, capped to avoid data-entry runaway.
        // feeSetting() returns 'no' for unset keys, so guard against non-numeric values.
        // Floor at 1 so a stray 0/negative setting can never block every application.
        $configuredMax = feeSetting('scholarship_max_per_fee');
        $maxPerFee = is_numeric($configuredMax) ? max(1, (int) $configuredMax) : 2;
        $activeCount = ScholarshipApplication::where('student_intake_course_fee_id', $fee->id)
            ->where('status', 1)->count();
        if ($activeCount >= $maxPerFee) {
            return redirect()->back()->with('failure', "This fee already has the maximum of {$maxPerFee} approved scholarships");
        }

        $application = ScholarshipApplication::create([
            'scholarship_id'                => $request->scholarship_id,
            'student_id'                    => $request->student_id,
            'intake_course_id'              => $fee->intake_course_id,
            'student_intake_course_fee_id'  => $fee->id,
            'justification'                 => $request->justification,
            'applied_by'                    => Auth::guard('user')->id(),
            'status'                        => 0,
        ]);

        activityLog('Admin', 'scholarship_application_id: ' . $application->id . ' created');

        // Auto-approve when approval is not required
        if (feeSetting('scholarship_require_approval') === 'no') {
            return $this->processApproval($application, null, Auth::guard('user')->id());
        }

        return redirect()->route('admin.scholarship.application.index')->with('success', 'Scholarship application submitted and is pending review');
    }

    public function show($id)
    {
        if (checkRole('scholarship_application', 'view') != true) {
            return abort(404);
        }

        $application = ScholarshipApplication::with([
            'scholarship',
            'student',
            'studentIntakeCourseFee.intakeCourse.course',
            'disbursements' => fn ($q) => $q->orderByRaw('sequence is null')->orderBy('sequence')->orderBy('id'),
            'disbursements.intakeSemester.semester',
        ])->findOrFail($id);

        activityLog('Admin', 'scholarship_application_id: ' . $id . ' disbursement schedule viewed');
        return view('scholarship::application.show', compact('application'));
    }

    public function review($id)
    {
        if (checkRole('scholarship_application', 'edit') != true) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'You do not have permission to review applications');
        }

        $application = ScholarshipApplication::with(['scholarship', 'student', 'studentIntakeCourseFee.intakeCourse.course'])->findOrFail($id);

        if ($application->status !== 0) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'This application has already been reviewed');
        }

        $discountAmount = (new DisbursementService())->totalAward($application->scholarship, $application->studentIntakeCourseFee);
        activityLog('Admin', 'scholarship_application_id: ' . $id . ' review page opened');
        return view('scholarship::application.review', compact('application', 'discountAmount'));
    }

    public function approve(Request $request, $id)
    {
        if (checkRole('scholarship_application', 'edit') != true) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'You do not have permission to approve applications');
        }

        $application = ScholarshipApplication::findOrFail($id);

        if ($application->status !== 0) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'This application has already been reviewed');
        }

        return $this->processApproval($application, $request->review_notes, Auth::guard('user')->id());
    }

    public function reject(Request $request, $id)
    {
        if (checkRole('scholarship_application', 'edit') != true) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'You do not have permission to reject applications');
        }

        $application = ScholarshipApplication::findOrFail($id);

        if ($application->status !== 0) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'This application has already been reviewed');
        }

        $application->update([
            'status'       => 2,
            'reviewed_by'  => Auth::guard('user')->id(),
            'reviewed_at'  => now(),
            'review_notes' => $request->review_notes,
        ]);

        activityLog('Admin', 'scholarship_application_id: ' . $application->id . ' rejected');
        return redirect()->route('admin.scholarship.application.index')->with('success', 'Scholarship application rejected');
    }

    private function processApproval(ScholarshipApplication $application, $notes, $reviewerId)
    {
        $application->update([
            'status'       => 1,
            'reviewed_by'  => $reviewerId,
            'reviewed_at'  => now(),
            'review_notes' => $notes,
        ]);

        // Generate the disbursement schedule and release the first (or the one-off) portion.
        (new DisbursementService())->generate($application);

        $released = (float) $application->disbursements()->where('state', 'released')->sum('actual_amount');
        $scheduled = $application->disbursements()->where('state', 'scheduled')->count();

        activityLog('Admin', 'scholarship_application_id: ' . $application->id . ' approved, released: ' . $released);

        $message = 'Scholarship approved. $' . number_format($released, 2) . ' applied now';
        if ($scheduled > 0) {
            $message .= ', with ' . $scheduled . ' more semester disbursement(s) scheduled';
        }
        return redirect()->route('admin.scholarship.application.index')->with('success', $message . '.');
    }

    /**
     * Revoke an approved scholarship: cancel scheduled disbursements and deactivate released
     * discounts. Discounts already consumed by paid installments are not refunded.
     */
    public function revoke(Request $request, $id)
    {
        if (checkRole('scholarship_application', 'edit') != true) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'You do not have permission to revoke applications');
        }

        $application = ScholarshipApplication::with('disbursements')->findOrFail($id);
        if ($application->status !== 1) {
            return redirect()->route('admin.scholarship.application.index')->with('failure', 'Only approved scholarships can be revoked');
        }

        foreach ($application->disbursements as $disbursement) {
            if ($disbursement->state === 'released' && $disbursement->fee_discount_id) {
                FeeDiscount::where('id', $disbursement->fee_discount_id)->update(['status' => 0]);
            }
            if (in_array($disbursement->state, ['scheduled', 'released'], true)) {
                $disbursement->update([
                    'state' => 'cancelled',
                    'state_reason' => $request->review_notes ?: 'Revoked by admin',
                    'evaluated_at' => now(),
                ]);
            }
        }

        // Deactivate any remaining active discounts for this application (belt and braces).
        FeeDiscount::where('scholarship_application_id', $application->id)->where('status', 1)->update(['status' => 0]);

        $application->update([
            'status'       => 3,
            'review_notes' => $request->review_notes,
            'reviewed_by'  => Auth::guard('user')->id(),
            'reviewed_at'  => now(),
        ]);

        activityLog('Admin', 'scholarship_application_id: ' . $application->id . ' revoked');
        return redirect()->route('admin.scholarship.application.index')->with('success', 'Scholarship revoked. Future disbursements cancelled; discounts already applied to paid installments are not refunded.');
    }
}
