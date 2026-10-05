<?php

namespace Modules\Trainer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Address\Entities\Address;
use Modules\Country\Entities\Country;
use Modules\Location\Entities\Location;
use Modules\Log\Entities\Log;
use Modules\Trainer\Entities\Trainer;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the trainers.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('trainer', 'view') == true) {
            activityLog('Admin', 'Opened Trainer Menu');
            $trainers = Trainer::orderBy('id', 'desc')->get();
            return view('trainer::index', compact('trainers'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new trainer.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('trainer', 'add') == true) {
            activityLog('Admin', 'Opened Create Trainer Page');
            $countries = Country::where('status', 1)->pluck('name', 'id');
            $teacher_id_readonly = teacherIdIsAutomatic();
            $teacher_id_value = $teacher_id_readonly ? reserveAutomaticTeacherId() : '';
            return view('trainer::create', compact('countries', 'teacher_id_readonly', 'teacher_id_value'));
        } else {
            return redirect()->route('admin.trainer.index')->with('failure', 'This user does not have permission to add trainer');
        }
    }

    /**
     * Store a newly created trainer in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->hasfile('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/trainers'), $imageName);
            $data['image'] = 'images/trainers/' . $imageName;
        } else {
            $data['image'] = 'files/avatar.png';
        }
        $data['password'] = Hash::make($data['password']);
        if (teacherIdIsAutomatic() && (empty($data['id_no']) || Trainer::where('id_no', $data['id_no'])->exists())) {
            $data['id_no'] = reserveAutomaticTeacherId();
        }
        $trainer = Trainer::create($data);
        $data['type_id'] = $trainer->id;
        $data['type'] = 'trainer';
        Address::create(array_merge($data, addressRequestData($request)));
        $name = userName('Trainer', $trainer->id);
        activityLog('Admin', $name . ' trainer created');
        return redirect()->route('admin.trainer.index')->with('success', 'Trainer has been added successfully');
    }

    /**
     * Show the specified trainer.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('trainer::show');
    }

    /**
     * Show the form for editing the specified trainer.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (checkRole('trainer', 'edit') == true) {
            $trainer = Trainer::findorfail($id);
            $districts = Location::where('status', 1)->where('location_id', $trainer->address->province)->pluck('name', 'id');
            $local_bodies = Location::where('status', 1)->where('location_id', $trainer->address->district)->pluck('name', 'id');
            $wards = Location::where('status', 1)->where('location_id', $trainer->address->local_body)->pluck('name', 'id');
            $toles = Location::where('status', 1)->where('location_id', $trainer->address->ward)->pluck('name', 'id');
            $teacher_id_readonly = teacherIdIsAutomatic();
            $teacher_id_value = $trainer->id_no;
            if ($teacher_id_readonly && $teacher_id_value == NULL) {
                $teacher_id_value = reserveAutomaticTeacherId();
            }
            activityLog('Admin', userName('Trainer', $trainer->id) . ' edit page opened');
            return view('trainer::edit', compact('trainer', 'districts', 'local_bodies', 'wards', 'toles', 'teacher_id_readonly', 'teacher_id_value'));
        } else {
            return redirect()->route('admin.trainer.index')->with('failure', 'This user does not have permission to add trainer');
        }
    }

    /**
     * Update the specified trainer in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/trainers'), $imageName);
            $data['image'] = 'images/trainers/' . $imageName;
        }
        if ($data['password'] == NULL) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $trainer = Trainer::find($id);
        if (teacherIdIsAutomatic()) {
            if ($trainer->id_no != NULL) {
                $data['id_no'] = $trainer->id_no;
            } elseif (empty($data['id_no']) || Trainer::where('id_no', $data['id_no'])->where('id', '<>', $id)->exists()) {
                $data['id_no'] = reserveAutomaticTeacherId();
            }
        }
        $trainer->fill($data)->save();

        $address = Address::where('type', 'trainer')->where('type_id', $id)->first();
        $addressData = addressRequestData($request);
        if ($address) {
            $address->fill($addressData)->save();
        } else {
            Address::create(array_merge($addressData, ['type' => 'trainer', 'type_id' => $id]));
        }
        activityLog('Admin', userName('Trainer', $trainer->id) . ' updated');
        return redirect()->route('admin.trainer.index')->with('success', 'Trainer has been updated successfully');
    }

    /**
     * Remove the specified trainer from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('trainer', 'delete') == true) {
            Trainer::where('id', $id)->update(['status' => 2]);
            return redirect()->back()->with('success', 'Trainer has been deleted successfully');
        } else {
            return redirect()->route('admin.trauber.index')->with('failure', 'This user does not have permission to delete student');
        }
    }

    public function dashboard($id)
    {
        if (checkRole('trainer_dashboard', 'view') == true) {
            activityLog('Admin', 'Opened Trainer Dashboard');
            Log::create([
                'user_type' => 'Trainer',
                'user_id' => $id,
                'action' => 'Logged In by Admin'
            ]);
            $trainer = Trainer::findorfail($id);
            if (Auth::guard('trainer')->loginUsingId($trainer->id)) {
                return redirect()->intended(route('trainer.dashboard'));
            }
        } else {
            return abort(404);
        }
    }
}
