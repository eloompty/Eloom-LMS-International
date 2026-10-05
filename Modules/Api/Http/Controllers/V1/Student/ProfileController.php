<?php

namespace Modules\Api\Http\Controllers\V1\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Student\Entities\Student;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     ** path="/student/profile",
     *   tags={"Students"},
     *   summary="Student Profile",
     *   description="Student Profile",
     *   operationId="studentProfile",     
     *   security={{"passport":{"*"}}},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentProfile(Request $request)
    {
        $student = Auth::guard('student_api')->user();
        if ($student) {
            activityLog('Student API', 'Opened Profile from App');
            $intake = $student->intake->where('status', 1)->first();
            if ($intake) {
                $intake = $intake->intakeCourse->intake->name;
            } else {
                $intake = '';
            }
            $socials = $student->social->where('status', 1);
            $social = [];
            foreach ($socials as $key => $value) {
                $social[] = [
                    'id' => $value->id,
                    'icon' => asset($value->social_category->image),
                    'name' => $value->social_category->name,
                    'link' => $value->social_link
                ];
            }
            $response = [
                'status' => true,
                'status_code' => 200,
                'message' => 'Student Profile',
                'data' => [
                    'id' => $student->id,
                    'name' => userName('Student', $student->id),
                    'date_of_birth' => $student->date_of_birth,
                    'passport_no' => $student->passport_no,
                    'citizenship' => $student->citizenship,
                    'phone' => $student->phone,
                    'mobile' => $student->mobile,
                    'email' => $student->email,
                    'image' => asset($student->image),
                    'id_no' => $student->id_no,
                    'country' => $student->country->name,
                    'address' => fullAddress('student', $student->id),
                    'overseas_address' => $student->overseas_address,
                    'overseas_country' => $student->overseasCountry->name,
                    'emergency_contact_person' => $student->emergency_contact_person,
                    'emergency_contact_number' => $student->emergency_contact_number,
                    'emergency_contact_relation' => $student->emergency_contact_relation,
                    'intake' => $intake,
                    'socials' => $social
                ]
            ];
            return response($response, 200);
        } else {
            $response = [
                'status' => false,
                'status_code' => 401,
                'message' => 'Student Not Found'
            ];
            return response($response, 401);
        }
    }

    /**
     * @OA\Post(
     ** path="/student/update/password",
     *   tags={"Students"},
     *   summary="Update Student Password",
     *   description="Update Student Password",
     *   operationId="studentUpdatePassword",
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="current_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="new_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function studentUpdatePassword(Request $request)
    {
        // Check required validation
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
        ]);
        if ($validator->fails()) {
            // Display message for validation error
            $errors = $validator->errors()->toArray();
            foreach ($errors as $key => $value) {
                $messages[$key] = $value[0];
            }
            $response =  [
                'status' => false,
                'status_code' => 422,
                'validation_errors' => $messages,
                'message' => implode(", ", $messages)
            ];
            return response($response, 422);
        } else {
            $student = Auth::guard('student_api')->user();
            if ($student) {
                // Check current password matches from database
                $current = Hash::check($request->current_password, $student->password);
                if ($current == true) {
                    // Check new password matches from database
                    $new = Hash::check($request->new_password, $student->password);
                    if ($new == true) {
                        $response = [
                            'status' => false,
                            'status_code' => 422,
                            'message' => 'New password cannot be as current password',
                        ];
                        activityLog('Student API', 'Update Password Failed');
                        return response($response, 422);
                    } else {
                        $change = Student::find($student->id);
                        $change->password = Hash::make($request->new_password);
                        $change->save();
                        $response = [
                            'status' => true,
                            'status_code' => 200,
                            'message' => 'Password has been updated',
                        ];
                        activityLog('Student API', 'Updated Password from App');
                        return response($response, 200);
                    }
                } else {
                    $response = [
                        'status' => false,
                        'status_code' => 422,
                        'message' => 'Wrong Password',
                    ];
                    activityLog('Student API', 'Update Password Failed');
                    return response($response, 422);
                }
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Student Not Found'
                ];
                return response($response, 401);
            }
        }
    }
}
