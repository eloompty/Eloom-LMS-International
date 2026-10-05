<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Log\Entities\Log;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentDevice;
use Modules\Student\Entities\StudentPasswordReset;

class LoginController extends Controller
{
    /* Student Login Page */
    public function home(Request $request)
    {
        $student = Auth::guard('student')->user();
        if ($student) {
            return redirect()->back();
        } else {
            return view('student::student.auth.login');
        }
    }

    /* Student Login */
    public function login(Request $request)
    {
        if (Auth::guard('student')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
            activityLog('Student', 'Logged In from web');
            return redirect()->intended(route('student.dashboard'));
        } else {
            return redirect()->back()->with('error',  'Invalid Email/Password.');
        }
    }

    /* Save FCM Token */
    public function saveToken(Request $request)
    {
        $student = Auth::guard('student')->user();
        if ($student) {
            $devices = StudentDevice::where('device_token', $request->fcm_token)->where('student_id', $student->id)->get();
            if (count($devices) == 0) {
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
                    $browser = 'Internet explorer';
                elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
                    $browser = 'Internet explorer';
                elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
                    $browser = 'Mozilla Firefox';
                elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
                    $browser = 'Google Chrome';
                elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
                    $browser = "Opera Mini";
                elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
                    $browser = "Opera";
                elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
                    $browser = "Safari";
                else
                    $browser = 'Other';
                StudentDevice::create([
                    'student_id' => $student->id,
                    'device_token' => $request->fcm_token,
                    'device_name' => $browser,
                    'device_type' => 'Web',
                ]);
            } else {
            }
            return response()->json(['message' => 'Device Token added successfully.']);
        } else {
            return response()->json(['error' => 'Trainer not found.']);
        }
    }

    /* Student Logout */
    public function logout()
    {
        $id = Auth::guard('student')->user()->id;
        $loginCheck = Log::where('user_type', 'Student')->where('user_id', $id)->where('action', 'Like', 'Logged In from web%')->orderBy('id', 'desc')->first();
        $loggedIn = $loginCheck->created_at;
        $loggOut = date_create(date('Y-m-d h:i:s'));

        // Calculating the difference between DateTime Objects
        $interval = date_diff($loggedIn, $loggOut);
        $min = $interval->days * 24 * 60;
        $min += $interval->h * 60;
        $min += $interval->i;

        activityLog('Student', 'Logged Out from web. Session Time: ' . $min . ' minutes');

        Auth::guard('student')->logout();
        return redirect()->route('student.login');
    }

    /* Forgot Password Page */
    public function forgotPassword()
    {
        return view('student::student.password.forgot');
    }

    /* Creating link to rest password */
    public function resetPassword(Request $request)
    {
        $data = $request->all();
        // Check the email in database 
        $student = Student::where('email', $data['email'])->first();
        if ($student) {
            $data['code'] = randomResetCode('Student');
            /* Creating link for reset password */
            StudentPasswordReset::create($data);
            $email = encrypt($data['email']);
            $code = encrypt($data['code']);
            $link = asset('student/password/reset/' . $email . '/' . $code);

            /* Send Password Reset Email */
            $to_name = userName('Student', $student->id);
            $to_email = $student->email;
            $data = [
                'name' => $to_name,
                'link' => $link,
            ];
            sendEmailSetting();
            Mail::send('student::student.email.passwordreset', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
            ->subject('Reset Password Request');
            // $message->from(env('MAIL_FROM_ADDRESS'),'LMS');
            });
            return redirect()->back()->with('success', 'Password Reset Email has been sent to your email address.');
        } else {
            return redirect()->back()->with('error',  'Email does not match from our database');
        }
    }

    /* Redirecting to Reset Password Page */
    public function resettingPassword($email, $code)
    {
        return view('student::student.password.reset', compact('email', 'code'));
    }

    /* Updating Password */
    public function updatePassword(Request $request)
    {
        $data = $request->all();
        if ($data['password'] == $data['confirm-password']) {
            $email = decrypt($data['email']);
            $code = decrypt($data['code']);
            // Checking whether email and code matches in the database 
            $check = StudentPasswordReset::where('email', $email)->where('code', $code)->first();
            if ($check && $check->status == 0) { 
                // Updating Password
                $check->update(['status' => 1]);
                $password = Hash::make($data['password']);
                Student::where('email', $email)->update([
                    'password' => $password
                ]);
                return redirect()->route('student.login')->with('success', 'Password has been reset');
            } else {
                return redirect()->back()->with('error',  'Link is broken. Please resend the email to reset password');
            }
        } else {
            return redirect()->back()->with('error',  'Password and Confirm Password must be same');
        }
    }
}
