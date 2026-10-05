<?php

namespace Modules\Scholarship\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Scholarship\Entities\FeeDiscount;

class FeeDiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index(Request $request)
    {
        if (checkRole('scholarship', 'view') != true) {
            return abort(404);
        }

        activityLog('Admin', 'Opened Fee Discounts / Revenue Impact Report');

        $query = FeeDiscount::with(['student', 'studentIntakeCourseFee.intakeCourse.course', 'scholarshipApplication.scholarship'])
            ->where('fee_discounts.status', 1);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('approved_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('approved_at', '<=', $request->to_date);
        }

        $discounts = $query->orderBy('approved_at', 'desc')->get();
        $totalDiscount = $discounts->sum('discount_amount');

        return view('scholarship::discount.index', compact('discounts', 'totalDiscount'))->with('no', 1);
    }

    public function destroy($id)
    {
        if (checkRole('scholarship', 'delete') != true) {
            return redirect()->back()->with('failure', 'You do not have permission to revoke discounts');
        }

        $discount = FeeDiscount::findOrFail($id);
        $discount->update(['status' => 2]);

        // Also reject the linked application if it exists
        if ($discount->scholarshipApplication) {
            $discount->scholarshipApplication->update(['status' => 2]);
        }

        activityLog('Admin', 'fee_discount_id: ' . $discount->id . ' revoked');
        return redirect()->back()->with('success', 'Fee discount revoked successfully');
    }
}
