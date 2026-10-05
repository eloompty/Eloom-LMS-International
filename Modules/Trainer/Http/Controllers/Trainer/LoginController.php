<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerDevice;
use Modules\Trainer\Entities\TrainerPasswordReset;

class LoginController extends Controller
{
    /* Trainer Login Page */
    public function home(Request $request)
    {
        $trainer = Auth::guard('trainer')->user();
        if ($trainer) {
            return redirect()->back();
        } else {
            return view('trainer::trainer.auth.login');
        }
    }

    /* Trainer Login */
    public function login(Request $request)
    {
        if (Auth::guard('trainer')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
            activityLog('Trainer', 'Logged In from web');
            return redirect()->intended(route('trainer.dashboard'));
        } else {
            return redirect()->back()->with('error',  'Invalid Email/Password.');
        }
    }

    /* Save FCM Token */
    public function saveToken(Request $request)
    {
        $trainer = Auth::guard('trainer')->user();
        if ($trainer) {
            $devices = TrainerDevice::where('device_token', $request->fcm_token)->where('trainer_id', $trainer->id)->get();
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
                TrainerDevice::create([
                    'trainer_id' => $trainer->id,
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

    /* Trainer Logout */
    public function logout()
    {
        activityLog('Trainer', 'Logged Out from web');
        Auth::guard('trainer')->logout();
        return redirect()->route('trainer.login');
    }

    /* Forgot Password Page */
    public function forgotPassword()
    {
        return view('trainer::trainer.password.forgot');
    }

    /* Creating link to reset password */
    public function resetPassword(Request $request)
    {
        $data = $request->all();
        // Check the email in database 
        $trainer = Trainer::where('email', $data['email'])->first();
        if ($trainer) {
            $data['code'] = randomResetCode('Trainer');
            /* Creating link for reset password */
            TrainerPasswordReset::create($data);
            $email = encrypt($data['email']);
            $code = encrypt($data['code']);
            $link = asset('trainer/password/reset/' . $email . '/' . $code);

            /* Send Password Reset Email */
            $to_name = userName('Trainer', $trainer->id);
            $to_email = $trainer->email;
            $data = [
                'name' => $to_name,
                'link' => $link,
            ];
            Mail::send('trainer::trainer.email.passwordreset', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
            ->subject('Reset Password Request');
            $message->from(env('MAIL_FROM_ADDRESS'),'LMS');
            });
            return redirect()->back()->with('success', 'Password Reset Email has been sent to your email address.');
        } else {
            return redirect()->back()->with('error',  'Email does not match from our database');
        }
    }

    /* Redirecting to Reset Password Page */
    public function resettingPassword($email, $code)
    {
        return view('trainer::trainer.password.reset', compact('email', 'code'));
    }

    /* Updating Password */
    public function updatePassword(Request $request)
    {
        $data = $request->all();
        if ($data['password'] == $data['confirm-password']) {
            $email = decrypt($data['email']);
            $code = decrypt($data['code']);
            // Checking whether email and code matches in the database 
            $check = TrainerPasswordReset::where('email', $email)->where('code', $code)->first();
            if ($check && $check->status == 0) {
                // Updating Password
                $check->update(['status' => 1]);
                $password = Hash::make($data['password']);
                Trainer::where('email', $email)->update([
                    'password' => $password
                ]);
                return redirect()->route('trainer.login')->with('success', 'Password has been reset');
            } else {
                return redirect()->back()->with('error',  'Link is broken. Please resend the email to reset password');
            }
        } else {
            return redirect()->back()->with('error',  'Password and Confirm Password must be same');
        }
    }
}
