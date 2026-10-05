<?php

namespace Modules\Api\Http\Controllers\V1\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Student\Entities\StudentDevice;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     ** path="/student/login",
     *   tags={"Students"},
     *   summary="Student Login",
     *   description="Student Login",
     *   operationId="studentLogin",
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           example="student@example.com"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           example="12345678"
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
    public function studentLogin(Request $request)
    {
        // Check required validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
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
            // Student Login
            if (Auth::guard('student')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
                $student = Auth::guard('student')->user();
                $accessToken = $student->createToken('StudentToken')->accessToken;

                // Check for Student Intake
                $studentIntake = $student->intake->where('status', 1)->first();
                if ($studentIntake) {
                    $intake = $studentIntake->intakeCourse->intake->name;
                    $course = $studentIntake->intakeCourse->course->course_name;
                } else {
                    $intake = "";
                    $course = "";
                }
                $response = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Successfully, Logged In!',
                    'token' => $accessToken,
                    'data' => [
                        'id' => $student->id,
                        'name' => userName('Student', $student->id),
                        'initial' => initialName('Student', $student->id),
                        'email' => $student->email,
                        'image' => asset($student->image),
                        'intake' => $intake,
                        'course' => $course,
                    ],
                ];
                activityLog('Student', 'Logged In From App');
                return response(
                    $response,
                    200,
                    ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE
                );
            } else {
                $response = [
                    'status' => false,
                    'status_code' => 400,
                    'message' => 'Email/Password does not match'
                ];
                return response($response, 400);
            }
        }
    }

    /**
     * @OA\Post(
     ** path="/student/device/register",
     *   tags={"Students"},
     *   summary="Student Device Register",
     *   description="Student Device Register",
     *   operationId="studentDeviceRegister",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="device_type",
     *      in="query",
     *      required=true,
     *      description="Android / iOS",
     *      @OA\Schema(
     *           type="string",
     *           example="Android"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="device_token",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           example="dHY0KPMemkxGu8abvbXzXN:APA91bHOuUXVMjPDWUzABoWYLewcN5DZR2kLZcGUNSEuGMd4CpwCZqN1hJ7Cr-rCWPTrgzNFu_vcy0fIQySuBhr5z5i_EmZ4buaOVQ3sNqGi7Ym7ifBGcS5OgduFxoQ28kV1ab-JPBZf"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="device_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           example="Samsung S21 Ultra 5G"
     *      )
     *   ),
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
    public function studentDeviceRegister(Request $request)
    {
        // Check required validation
        $validator = Validator::make($request->all(), [
            'device_type' => 'required',
            'device_name' => 'required',
            'device_token' => 'required'
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
                // Check if device already exists
                $device = StudentDevice::where('student_id', $student->id)->where('device_type', $request->device_type)->where('device_token', $request->device_token)->first();
                if (!$device) {
                    $data = $request->all();
                    $data['student_id'] = $student->id;
                    StudentDevice::create($data);
                    activityLog('Student API', $data['device_name'] . ' Device Registered');
                } else if ($device->status == 0) {
                    $device->update(['status' => 1]);
                    activityLog('Student API', $device->device_name . ' Device Status Changed to Active');
                } else {
                    activityLog('Student API', $device->device_name . ' Device Logged In');
                }
                $response = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Device Registered successfully'
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
    }

    /**
     * @OA\Post(
     ** path="/student/logout",
     *   tags={"Students"},
     *   summary="Student Logout",
     *   description="Student Logout",
     *   operationId="studentLogout",     
     *   security={{"passport":{"*"}}},
     *   @OA\Parameter(
     *      name="device_type",
     *      in="query",
     *      required=true,
     *      description="Android / iOS",
     *      @OA\Schema(
     *           type="string",
     *           example="Android"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="device_token",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string",
     *           example="dHY0KPMemkxGu8abvbXzXN:APA91bHOuUXVMjPDWUzABoWYLewcN5DZR2kLZcGUNSEuGMd4CpwCZqN1hJ7Cr-rCWPTrgzNFu_vcy0fIQySuBhr5z5i_EmZ4buaOVQ3sNqGi7Ym7ifBGcS5OgduFxoQ28kV1ab-JPBZf"
     *      )
     *   ),
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
    public function studentLogout(Request $request)
    {
         // Check required validation
         $validator = Validator::make($request->all(), [
            'device_type' => 'required',
            'device_token' => 'required'
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
                // Check if device already exists
                $device = StudentDevice::where('student_id', $student->id)->where('device_type', $request->device_type)->where('device_token', $request->device_token)->first();
                if ($device) {
                    $device->update(['status' => 0]);
                    activityLog('Student API', $device->device_name . ' Device Logged Out');
                    $response = [
                        'status' => true,
                        'status_code' => 200,
                        'message' => 'Device Logged Out'
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'status_code' => 400,
                        'message' => 'Device Not Found'
                    ];
                    return response($response, 400);
                }
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
    }
}
