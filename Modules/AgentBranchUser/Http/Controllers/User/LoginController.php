<?php

namespace Modules\AgentBranchUser\Http\Controllers\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\AgentBranchUser\Entities\AgentBranchUser;
use Modules\AgentBranchUser\Entities\AgentBranchUserDevice;
use Modules\AgentBranchUser\Entities\AgentBranchUserPasswordReset;

class LoginController extends Controller
{
    /* Agent Branch User Login Page */
    public function home(Request $request)
    {
        $user = Auth::guard('agent_branch_user')->user();
        if ($user) {
            return redirect()->back();
        } else {
            return view('agentbranchuser::user.auth.login');
        }
    }

    /* Agent Branch User Login */
    public function login(Request $request)
    {
        if (Auth::guard('agent_branch_user')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
            activityLog('Agent Branch User', 'Logged In from web');
            return redirect()->intended(route('branch-user.dashboard'));
        } else {
            return redirect()->back()->with('error',  'Invalid Email/Password.');
        }
    }

    /* Save FCM Token */
    public function saveToken(Request $request)
    {
        $agent_branch_user = Auth::guard('agent_branch_user')->user();
        if ($agent_branch_user) {
            $devices = AgentBranchUserDevice::where('device_token', $request->fcm_token)->where('agent_branch_user_id', $agent_branch_user->id)->get();
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
                AgentBranchUserDevice::create([
                    'agent_branch_user_id' => $agent_branch_user->id,
                    'device_token' => $request->fcm_token,
                    'device_name' => $browser,
                    'device_type' => 'Web',
                ]);
            } else {
            }
            return response()->json(['message' => 'Device Token added successfully.']);
        } else {
            return response()->json(['error' => 'Agent Branch User not found.']);
        }
    }

    /* Agent Logout */
    public function logout()
    {
        activityLog('Agent Branch User', 'Logged Out from web.');
        Auth::guard('agent_branch_user')->logout();
        return redirect()->route('branch-user.login');
    }

    /* Forgot Password Page */
    public function forgotPassword()
    {
        return view('agentbranchuser::user.password.forgot');
    }

    /* Creating link to rest password */
    public function resetPassword(Request $request)
    {
        $data = $request->all();
        // Check the email in database 
        $agent_branch_user = AgentBranchUser::where('email', $data['email'])->first();
        if ($agent_branch_user) {
            $data['code'] = randomResetCode('Agent');
            /* Creating link for reset password */
            AgentBranchUserPasswordReset::create($data);
            $email = encrypt($data['email']);
            $code = encrypt($data['code']);
            $link = asset('branch-user/password/reset/' . $email . '/' . $code);

            /* Send Password Reset Email */
            $to_name = $agent_branch_user->name;
            $to_email = $agent_branch_user->email;
            $data = [
                'name' => $to_name,
                'link' => $link,
            ];
            Mail::send('agentbranchuser::user.email.passwordreset', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                    ->subject('Reset Password Request');
                $message->from(env('MAIL_FROM_ADDRESS'), 'LMS');
            });
            return redirect()->back()->with('success', 'Password Reset Email has been sent to your email address.');
        } else {
            return redirect()->back()->with('error',  'Email does not match from our database');
        }
    }

    /* Redirecting to Reset Password Page */
    public function resettingPassword($email, $code)
    {
        return view('agentbranchuser:user.password.reset', compact('email', 'code'));
    }

    /* Updating Password */
    public function updatePassword(Request $request)
    {
        $data = $request->all();
        if ($data['password'] == $data['confirm-password']) {
            $email = decrypt($data['email']);
            $code = decrypt($data['code']);
            // Checking whether email and code matches in the database 
            $check = AgentBranchUserPasswordReset::where('email', $email)->where('code', $code)->first();
            if ($check && $check->status == 0) {
                // Updating Password
                $check->update(['status' => 1]);
                $password = Hash::make($data['password']);
                AgentBranchUser::where('email', $email)->update([
                    'password' => $password
                ]);
                return redirect()->route('branch-user.login')->with('success', 'Password has been reset');
            } else {
                return redirect()->back()->with('error',  'Link is broken. Please resend the email to reset password');
            }
        } else {
            return redirect()->back()->with('error',  'Password and Confirm Password must be same');
        }
    }
}
