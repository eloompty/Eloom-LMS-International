<?php

namespace Modules\User\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Address\Entities\Address;
use Modules\Agent\Entities\Agent;
use Modules\Assignment\Entities\AssignmentSubmission;
use Modules\Company\Entities\CompanyDeliverySite;
use Modules\Country\Entities\Country;
use Modules\Course\Entities\Course;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Location\Entities\Location;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentDeliverySite;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallment;
use Modules\Student\Entities\StudentIntakeCourseFeeInstallmentPayment;
use Modules\Ticket\Entities\Ticket;
use Modules\Trainer\Entities\Trainer;
use Modules\User\Entities\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /* Dashboard */
    public function dashboard()
    {
        activityLog('Admin', 'Opened Dashboard');
        $ids = getDeliverySiteIds();
        $total_courses = Course::whereIn('status', [0, 1])->count();
        $total_students = Student::where('is_enrolled', 1)->whereIn('status', [0, 1])->count();
        $trainers = Trainer::whereIn('status', [0, 1])->get();
        $total_trainers = $trainers->count();
        $all_intakes = Intake::whereIn('status', [0, 1])->get();
        $total_intakes = $all_intakes->count();
        $total_opened_tickets = Ticket::where('status', 1)->count();
        $total_in_progress_tickets = Ticket::where('status', 2)->count();
        $submissions = AssignmentSubmission::join('assignments', 'assignments.id', '=', 'assignment_submissions.assignment_id')
            ->join('students', 'students.id', '=', 'assignment_submissions.student_id')
            ->join('intake_units', 'intake_units.id', '=', 'assignments.intake_unit_id')
            ->join('intake_courses', 'intake_courses.id', '=', 'intake_units.intake_course_id')
            ->join('courses', 'courses.id', '=', 'intake_courses.course_id')
            ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
            ->where(function ($query) use ($ids) {
                $query->whereNull('course_delivery_sites.course_id')
                    ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
            })
            ->where('students.status', 1)
            ->select('assignment_submissions.*')
            ->orderBy('id', 'desc')->paginate(5);
        $opened_tickets = Ticket::where('status', 1)->get();
        $today = Carbon::now();
        $next_week = Carbon::now()->addDay(7);
        $payment_dues = StudentIntakeCourseFeeInstallment::whereBetween('due_date', [$today, $next_week])->where('status', 1)->paginate(5);
        if (count($all_intakes) > 0) {
            foreach ($all_intakes as $key => $value) {
                $intakeCourses = IntakeCourse::where('intake_id', $value->id)->get();
                if (count($intakeCourses) > 0)
                    foreach ($intakeCourses as $intakeCourse) {
                        $count = StudentIntakeCourse::where('intake_course_id', $intakeCourse->id)->count();
                    }
                else {
                    $count = 0;
                }
                $chart_intakes[] = $value->name;
                $chart_students[] = $count;
            }
        } else {
            $chart_intakes = [];
            $chart_students = [];
        }
        $address_format = currentAddressFormat();
        $address_chart_title = $this->studentAddressChartTitle($address_format);
        [$top_provinces, $top_provinces_count, $top_colors] = $this->studentAddressChartData($address_format);
        $all_countries = Country::join('students', 'students.citizenship_country', '=', 'countries.id')
            ->where('students.status', 1)->distinct('countries.id')->get('countries.id');
        $top_countries = $all_countries->take(5);
        $top_country = [];
        $top_count = [];
        $countries = [];
        $student_counts = [];
        foreach ($top_countries as $key => $value) {
            $students = Student::where('citizenship_country', $value->id)->count();
            $country = Country::find($value->id);
            $top_country[] = $country->name;
            $top_count[] = $students;
        }
        foreach ($all_countries as $key => $value) {
            $students = Student::where('citizenship_country', $value->id)->count();
            $country = Country::find($value->id);
            $countries[] = $country->name;
            $student_counts[] = $students;
        }
        $colors = randomHexColor(count($all_countries));

        $top_agents = Student::join('student_agents', 'student_agents.student_id', '=', 'students.id')
            ->select(DB::raw('count(*) as count, agent_id'))->groupBy('agent_id')->orderBy('count', 'desc')->take(5)->get();
        if (count($top_agents) > 0) {
            foreach ($top_agents as $key => $value) {
                $agent = Agent::find($value->agent_id);
                $top_agent[] = acronym($agent->company_name);
                $top_agent_count[] = $value->count;
            }
        } else {
            $top_agent = [];
            $top_agent_count = [];
        }
        $delivery_sites = CompanyDeliverySite::where('status', 1)->get();
        foreach ($delivery_sites as $site) {
            $students = StudentDeliverySite::join('students', 'students.id', '=', 'student_delivery_sites.student_id')
                ->whereIn('students.status', [0, 1])
                ->where('students.is_enrolled', 1)
                ->where('student_delivery_sites.company_delivery_site_id', $site->id)
                ->where('student_delivery_sites.status', 1)
                ->select('student_delivery_sites.id')
                ->count();
            $site_label[] = $site->site_name;
            $site_student[] = $students;
        }
        $data = [
            'labels' => $site_label,
            'data' => $site_student,
        ];
        $total_fees = StudentIntakeCourseFeeInstallment::sum('amount');
        $total_fee_received = StudentIntakeCourseFeeInstallmentPayment::sum('paid_amount');
        $total_due_fees = $total_fees - $total_fee_received;

        return view('user::dashboard.dashboard', compact(
            'total_courses',
            'total_students',
            'total_trainers',
            'total_intakes',
            'submissions',
            'payment_dues',
            'chart_intakes',
            'chart_students',
            'trainers',
            'top_country',
            'top_count',
            'countries',
            'student_counts',
            'colors',
            'top_agent',
            'top_agent_count',
            'data',
            'total_fee_received',
            'total_due_fees',
            'top_provinces',
            'top_provinces_count',
            'top_colors',
            'address_chart_title',
            'total_opened_tickets',
            'total_in_progress_tickets',
            'opened_tickets'
        ));
    }

    private function studentAddressChartTitle($format)
    {
        if ($format == 'nepal') {
            return 'Top Students By Province';
        }

        if ($format == 'uae') {
            return 'Top Students By Emirate';
        }

        if (in_array($format, ['international', 'uk'])) {
            return 'Top Students By State/City';
        }

        return 'Top Students By State';
    }

    private function studentAddressChartData($format)
    {
        if ($format == 'nepal') {
            $provinces = Location::where('location_id', 0)->where('status', 1)->get();
            $labels = [];
            $counts = [];

            foreach ($provinces as $province) {
                $students = Address::join('students', 'students.id', '=', 'addresses.type_id')
                    ->whereIn('addresses.type', ['student', 'Student'])
                    ->where('addresses.province', $province->id)
                    ->where('students.is_enrolled', 1)
                    ->whereIn('students.status', [0, 1])
                    ->count();

                $labels[] = trim(preg_replace('/.*: (.*?) Pradesh/', '$1', $province->name));
                $counts[] = $students;
            }

            return [$labels, $counts, randomHexColor(count($labels))];
        }

        $addresses = Address::join('students', 'students.id', '=', 'addresses.type_id')
            ->whereIn('addresses.type', ['student', 'Student'])
            ->where('students.is_enrolled', 1)
            ->whereIn('students.status', [0, 1])
            ->select('addresses.*')
            ->get();

        $grouped = [];
        foreach ($addresses as $address) {
            $label = $this->studentAddressChartLabel($address, $format);
            if ($label == null || $label == '') {
                continue;
            }

            if (!isset($grouped[$label])) {
                $grouped[$label] = 0;
            }
            $grouped[$label]++;
        }

        arsort($grouped);
        $grouped = array_slice($grouped, 0, 10, true);

        return [
            array_keys($grouped),
            array_values($grouped),
            randomHexColor(count($grouped)),
        ];
    }

    private function studentAddressChartLabel($address, $format)
    {
        if ($format == 'uae') {
            return $address->emirate ?: $address->city;
        }

        if ($format == 'uk') {
            return $address->county ?: $address->city;
        }

        if ($format == 'international') {
            return $address->state_region ?: $address->state ?: $address->city;
        }

        if ($format == 'india') {
            return $address->state ?: $address->city ?: $address->district;
        }

        return $address->state ?: $address->city ?: $address->suburb;
    }

    /* Toggle from dark to light and vice versa */
    public function toggleTheme(Request $request)
    {
        $id = Auth::guard('user')->user()->id;
        $user = User::where('id', $id)->update(['theme' => $request->theme]);
        activityLog('Admin', 'Theme Updated to ' . $request->theme);
        $data = [
            'success' => true,
            'theme' => $request->theme,
            'message' => 'Theme toggled' . $request->theme
        ];
        return response()->json($data);
    }

    /**
     * Display User/Admin Profile.
     * @return Renderable
     */
    public function index()
    {
        activityLog('Admin', 'Opened Profile');
        $countries = Country::pluck('name', 'id');
        return view('user::index', compact('countries'));
    }

    /**
     * Update User/Admin Profile.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($data['password'] == NULL) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/users'), $imageName);
            $data['image'] = 'images/users/' . $imageName;
        }
        unset($data['_token']);
        User::where('id', $id)->update($data);
        activityLog('Admin', 'Profile Updated');
        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}
