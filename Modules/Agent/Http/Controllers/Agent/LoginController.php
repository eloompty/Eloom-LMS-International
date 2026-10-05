<?php

namespace Modules\Agent\Http\Controllers\Agent;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\AgentDevice;
use Modules\Agent\Entities\AgentPasswordReset;

class LoginController extends Controller
{
    /* Agent Login Page */
    public function home(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        if ($agent) {
            return redirect()->back();
        } else {
            return view('agent::agent.auth.login');
        }
    }

    /* Agent Login */
    public function login(Request $request)
    {
        if (Auth::guard('agent')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
            activityLog('Agent', 'Logged In from web');
            return redirect()->intended(route('agent.dashboard'));
        } else {
            return redirect()->back()->with('error',  'Invalid Email/Password.');
        }
    }

     /* Save FCM Token */
     public function saveToken(Request $request)
     {
         $agent = Auth::guard('agent')->user();
         if ($agent) {
             $devices = AgentDevice::where('device_token', $request->fcm_token)->where('agent_id', $agent->id)->get();
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
                 AgentDevice::create([
                     'agent_id' => $agent->id,
                     'device_token' => $request->fcm_token,
                     'device_name' => $browser,
                     'device_type' => 'Web',
                 ]);
             } else {
             }
             return response()->json(['message' => 'Device Token added successfully.']);
         } else {
             return response()->json(['error' => 'Agent not found.']);
         }
     }

    /* Agent Logout */
    public function logout()
    {
        activityLog('Agent', 'Logged Out from web.');
        Auth::guard('agent')->logout();
        return redirect()->route('agent.login');
    }

    /* Forgot Password Page */
    public function forgotPassword()
    {
        return view('agent::agent.password.forgot');
    }

    /* Creating link to rest password */
    public function resetPassword(Request $request)
    {
        $data = $request->all();
        // Check the email in database 
        $agent = Agent::where('email', $data['email'])->first();
        if ($agent) {
            $data['code'] = randomResetCode('Agent');
            /* Creating link for reset password */
            AgentPasswordReset::create($data);
            $email = encrypt($data['email']);
            $code = encrypt($data['code']);
            $link = asset('agent/password/reset/' . $email . '/' . $code);

            /* Send Password Reset Email */
            $to_name = $agent->name;
            $to_email = $agent->email;
            $data = [
                'name' => $to_name,
                'link' => $link,
            ];
            Mail::send('agent::agent.email.passwordreset', $data, function($message) use ($to_name, $to_email) {
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
        return view('agent::agent.password.reset', compact('email', 'code'));
    }

    /* Updating Password */
    public function updatePassword(Request $request)
    {
        $data = $request->all();
        if ($data['password'] == $data['confirm-password']) {
            $email = decrypt($data['email']);
            $code = decrypt($data['code']);
            // Checking whether email and code matches in the database 
            $check = AgentPasswordReset::where('email', $email)->where('code', $code)->first();
            if ($check && $check->status == 0) { 
                // Updating Password
                $check->update(['status' => 1]);
                $password = Hash::make($data['password']);
                Agent::where('email', $email)->update([
                    'password' => $password
                ]);
                return redirect()->route('agent.login')->with('success', 'Password has been reset');
            } else {
                return redirect()->back()->with('error',  'Link is broken. Please resend the email to reset password');
            }
        } else {
            return redirect()->back()->with('error',  'Password and Confirm Password must be same');
        }
    }
}
