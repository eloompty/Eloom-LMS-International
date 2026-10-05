<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Address\Entities\Address;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\CompanyDeliverySite;
use Modules\Country\Entities\Country;
use Modules\User\Entities\User;
use Modules\User\Entities\UserDevice;
use Modules\User\Entities\UserPasswordReset;
use Modules\User\Http\Controllers\LoginController as DefaultLoginController;

class LoginController extends Controller
{
    /* Home Page */
    public function home(Request $request)
    {
        // Check if user exists
        $user = User::count();
        if ($user > 0) {
            $url = url()->previous();
            if (str_contains($url, url('') . '/student') && !strpos($url, $request->root . '/student/login')) {
                return redirect('student/login');
            } elseif (str_contains($url, url('') . '/trainer') && !strpos($url, $request->root . '/trainer/login')) {
                return redirect('trainer/login');
            } elseif (str_contains($url, url('') . '/agent') && !strpos($url, $request->root . '/agent/login')) {
                return redirect('agent/login');
            } elseif (str_contains($url, url('') . '/branch-user') && !strpos($url, $request->root . '/branch-user/login')) {
                return redirect('branch-user/login');
            } else {
                $user = Auth::guard('user')->user();
                if ($user) {
                    return redirect()->back();
                } else {
                    return view('user::auth.login');
                }
            }
        } else {
            $countries = Country::pluck('name', 'id');
            return view('user::auth.register', compact('countries'));
        }
    }

    /* Register Page */
    public function register(Request $request)
    {
        // First-run setup only. Once an account exists this endpoint is closed:
        // home() already hides the form, and without this check the route would
        // let anyone create a super admin at any time.
        abort_if(User::count() > 0, 404);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'family_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'image' => ['nullable', 'image', 'max:2048'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'site_name' => ['required', 'string', 'max:255'],
        ]);

        // Upload User Image
        if ($request->has('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/user/'), $imageName);
            $image = 'images/user/' . $imageName;
        } else {
            $image = 'themes/AdminLTE/dist/img/avatar.png';
        }
        // Register User
        $user = User::create([
            'first_name' => $request->first_name,
            'family_name' => $request->family_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $image,
            'user_type' => 'super_admin',
            'country_id' => 156,
            'password' => Hash::make($request->password),
            'theme' => 'theme3',
        ]);
        // Upload Company Logo
        if ($request->has('logo')) {
            $logoName = time() . '.' . request()->logo->getClientOriginalExtension();
            request()->logo->move(public_path('images/company/'), $logoName);
            $logo = 'images/company/' . $logoName;
        } else {
            $logo = 'themes/AdminLTE/dist/img/avatar.png';
        }
        // Register Company
        $company = Company::create([
            'company_name' => $request->company_name,
            'company_ceo' => $request->company_ceo,
            'email' => $request->company_email,
            'phone' => $request->company_phone,
            'logo' => $logo,
        ]);
        // Grab company Id
        $company_id = $company->id;
        Address::create(array_merge(addressRequestData($request, 'company_'), [
            'type' => 'company',
            'type_id' => $company->id,
        ]));
        // Register company delivery site
        $delivery_site = CompanyDeliverySite::create([
            'company_id' => $company_id,
            'site_name' => $request->site_name,
            'phone' => $request->site_phone,
        ]);
        Address::create(array_merge(addressRequestData($request, 'company_delivery_site_'), [
            'type' => 'company_delivery_site',
            'type_id' => $delivery_site->id,
        ]));
        // User login
        Auth::guard('user')->login($user);
        activityLog('Admin', 'Super Admin Created. Company, Company Address, Delivery Site and Delivery Site Address Created');
        return redirect()->route('admin.dashboard');
    }

    /* User/Admin Login */
    public function login(Request $request)
    {
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
            activityLog('Admin', 'Logged In');
            return redirect()->intended(route('admin.dashboard'));
        } else {
            return redirect()->back()->with('error',  'Invalid Email/Password.');
        }
    }

    /* Save FCM Token */
    public function saveToken(Request $request)
    {
        $user = Auth::guard('user')->user();
        if ($user) {
            $devices = UserDevice::where('device_token', $request->fcm_token)->where('user_id', $user->id)->get();
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
                UserDevice::create([
                    'user_id' => $user->id,
                    'device_token' => $request->fcm_token,
                    'device_name' => $browser,
                    'device_type' => 'Web',
                ]);
            }
            return response()->json(['message' => 'Device Token added successfully.']);
        } else {
            return response()->json(['error' => 'Trainer not found.']);
        }
    }

    /* User/Admin Logout */
    public function logout()
    {
        activityLog('Admin', 'Logged Out');
        Auth::guard('user')->logout();
        return redirect()->route('login');
    }

    /* Forgot Password Page */
    public function forgotPassword()
    {
        return view('user::password.forgot');
    }

    /* Creating link to rest password */
    public function resetPassword(Request $request)
    {
        $data = $request->all();
        // Check the email in database
        $user = User::where('email', $data['email'])->first();
        if ($user) {
            $data['code'] = randomResetCode('Admin');
            /* Creating link for reset password */
            UserPasswordReset::create($data);
            $email = encrypt($data['email']);
            $code = encrypt($data['code']);
            $link = asset('admin/password/reset/' . $email . '/' . $code);
            /* Send Password Reset Email */
            $to_name = userName('Admin', $user->id);
            $to_email = $user->email;
            $data = [
                'name' => $to_name,
                'link' => $link,
            ];
            Mail::send('user::email.passwordreset', $data, function ($message) use ($to_name, $to_email) {
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
        return view('user::password.reset', compact('email', 'code'));
    }

    /* Updating Password */
    public function updatePassword(Request $request)
    {
        $data = $request->all();
        if ($data['password'] == $data['confirm-password']) {
            $email = decrypt($data['email']);
            $code = decrypt($data['code']);
            // Checking whether email and code matches in the database
            $check = UserPasswordReset::where('email', $email)->where('code', $code)->first();
            if ($check && $check->status == 0) {
                // Updating Password
                $check->update(['status' => 1]);
                $password = Hash::make($data['password']);
                User::where('email', $email)->update([
                    'password' => $password
                ]);
                return redirect()->route('login')->with('success', 'Password has been reset');
            } else {
                return redirect()->back()->with('error',  'Link is broken. Please resend the email to reset password');
            }
        } else {
            return redirect()->back()->with('error',  'Password and Confirm Password must be same');
        }
    }
}
