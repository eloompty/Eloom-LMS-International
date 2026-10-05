<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Mail;
use Modules\Address\Entities\Address;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\CompanyDeliverySite;
use Modules\Country\Entities\Country;
use Modules\Course\Entities\CourseDeliverySite;
use Modules\Email\Entities\Email;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Location\Entities\Location;
use Modules\Log\Entities\Log;
use Modules\Notification\Entities\Notification;
use Modules\Report\Entities\ReportTemplate;
use Modules\Setting\Entities\Setting;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentOffer;
use Modules\Student\Entities\StudentPasswordReset;
use Modules\Student\Entities\StudentTemplateData;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerPasswordReset;
use Modules\User\Entities\User;
use Modules\User\Entities\UserDeliverySite;
use Modules\User\Entities\UserPasswordReset;
use Modules\User\Entities\UserRole;

use Illuminate\Support\Facades\Log as FacadeLog;

/* Save User Activity Logs */

function activityLog($user_type, $action)
{
    if ($user_type == 'Admin') {
        $user = 'user';
    } elseif ($user_type == 'Student') {
        $user = 'student';
    } elseif ($user_type == 'Student API') {
        $user = 'student_api';
        $user_type = 'Student';
    } elseif ($user_type == 'Trainer') {
        $user = 'trainer';
    } elseif ($user_type == 'Trainer API') {
        $user = 'trainer_api';
        $user_type = 'Trainer';
    } elseif ($user_type == 'Agent') {
        $user = 'agent';
    } elseif ($user_type == 'Agent Branch User') {
        $user = 'agent_branch_user';
    }
    $user_id = Auth::guard($user)->user()->id;
    Log::create([
        'user_type' => $user_type,
        'user_id' => $user_id,
        'action' => $action
    ]);
}

/* Check the Admin Role Permission */
function checkRole($key, $value)
{
    $user_type = Auth::guard('user')->user()->user_type;
    $check = UserRole::where('user_type', $user_type)->where('key', $key)->where('value', $value)->where('status', 1)->first();
    if ($check) {
        return true;
    } else {
        return false;
    }
}

/* Get full name of the user */
function userName($user_type, $user_id)
{
    if ($user_type == 'Student') {
        $user = Student::find($user_id);
    } elseif ($user_type == 'Trainer') {
        $user = Trainer::find($user_id);
    } else {
        $user = User::find($user_id);
    }
    if ($user) {
        return $user->salutation . ' ' . $user->first_name . ' ' . $user->family_name;
    } else {
        return '-';
    }
}

/* Get profile image of the user */
function userImage($user_type, $user_id)
{
    if ($user_type == 'Student') {
        $user = Student::find($user_id);
    } elseif ($user_type == 'Trainer') {
        $user = Trainer::find($user_id);
    } else {
        $user = User::find($user_id);
    }
    if ($user) {
        return asset($user->image);
    } else {
        return '-';
    }
}

/* Check status of unit */
function checkUnitLock($status)
{
    if ($status == 1) {
        return False;
    } else {
        return True;
    }
}

/* Get Location Name By Location Id */
function getLocationNameById($id)
{
    $location = Location::find($id);
    return $location->name;
}

function addressFormatOptions()
{
    return [
        'international' => 'International',
        'nepal' => 'Nepal',
        'india' => 'India',
        'australia' => 'Australia',
        'usa' => 'USA',
        'uk' => 'UK',
        'uae' => 'UAE',
    ];
}

function currentAddressFormat()
{
    $format = getSettingValue('address_format');
    return array_key_exists($format, addressFormatOptions()) ? $format : 'international';
}

function addressFieldNames()
{
    return [
        'country_id',
        'province',
        'district',
        'local_body',
        'ward',
        'tole',
        'address',
        'address_line_1',
        'address_line_2',
        'building_name',
        'building_number',
        'flat_unit',
        'street_no',
        'street_address',
        'p_o_box',
        'suburb',
        'city',
        'state',
        'state_region',
        'county',
        'postal_code',
        'zip_code',
        'area',
        'emirate',
    ];
}

function addressRequestData($request, $prefix = '', $format = null)
{
    $data = [
        'address_format' => $format ?: currentAddressFormat(),
    ];

    foreach (addressFieldNames() as $field) {
        $requestKey = $prefix . $field;
        if ($request->has($requestKey)) {
            $data[$field] = $request->input($requestKey);
        }
    }

    if (!isset($data['postal_code']) && isset($data['zip_code'])) {
        $data['postal_code'] = $data['zip_code'];
    }
    if (!isset($data['zip_code']) && isset($data['postal_code'])) {
        $data['zip_code'] = $data['postal_code'];
    }
    if (!isset($data['building_name']) && isset($data['building_number'])) {
        $data['building_name'] = $data['building_number'];
    }

    return $data;
}

function addressFormatForCountry($countryId): string
{
    $country = Country::find($countryId);
    if (!$country) {
        return 'international';
    }

    $map = [
        'NP' => 'nepal', 'IN' => 'india', 'AU' => 'australia',
        'US' => 'usa',   'GB' => 'uk',    'AE' => 'uae',
    ];

    return $map[$country->code ?? ''] ?? 'international';
}

/* Build address data from an indexed array block (used for the multi-address list) */
function addressRequestDataFromArray($block, $format = null)
{
    $block = is_array($block) ? $block : [];
    $data = [
        'address_format' => $format ?: currentAddressFormat(),
    ];

    foreach (addressFieldNames() as $field) {
        if (array_key_exists($field, $block)) {
            $data[$field] = $block[$field];
        }
    }

    if (!isset($data['postal_code']) && isset($data['zip_code'])) {
        $data['postal_code'] = $data['zip_code'];
    }
    if (!isset($data['zip_code']) && isset($data['postal_code'])) {
        $data['zip_code'] = $data['postal_code'];
    }
    if (!isset($data['building_name']) && isset($data['building_number'])) {
        $data['building_name'] = $data['building_number'];
    }

    return $data;
}

function addressCountryName($countryId)
{
    if (!$countryId) {
        return null;
    }

    $country = Country::find($countryId);
    return $country ? $country->name : $countryId;
}

function locationNameOrNull($id)
{
    if (!$id) {
        return null;
    }

    $location = Location::find($id);
    return $location ? $location->name : $id;
}

function compactAddressParts($parts)
{
    return collect($parts)
        ->filter(function ($part) {
            return $part !== null && $part !== '';
        })
        ->unique()
        ->implode(', ');
}

function formattedAddress($address)
{
    if (!$address) {
        return '-';
    }

    $hasNepalFields = $address->province || $address->district || $address->local_body || $address->ward || $address->tole;
    $hasAustralianFields = $address->street_no || $address->street_address || $address->suburb || $address->zip_code;
    $format = $address->address_format ?: ($hasNepalFields ? 'nepal' : ($hasAustralianFields ? 'australia' : currentAddressFormat()));

    if ($format === 'nepal') {
        $parts = [
            $address->address,
            locationNameOrNull($address->tole),
            locationNameOrNull($address->ward),
            locationNameOrNull($address->local_body),
            locationNameOrNull($address->district),
            locationNameOrNull($address->province),
            addressCountryName($address->country_id),
        ];
    } elseif ($format === 'australia') {
        $street = compactAddressParts([$address->street_no, $address->street_address]);
        $parts = [
            $address->building_name ?: $address->building_number,
            $address->flat_unit,
            $street,
            $address->p_o_box,
            $address->suburb,
            $address->state,
            $address->postal_code ?: $address->zip_code,
            addressCountryName($address->country_id),
        ];
    } elseif ($format === 'india') {
        $parts = [
            $address->building_name ?: $address->building_number,
            $address->street_address,
            $address->area,
            $address->city,
            $address->district,
            $address->state,
            $address->postal_code ?: $address->zip_code,
            addressCountryName($address->country_id),
        ];
    } elseif ($format === 'usa') {
        $parts = [
            $address->address_line_1,
            $address->address_line_2,
            $address->city,
            $address->state,
            $address->zip_code ?: $address->postal_code,
            addressCountryName($address->country_id),
        ];
    } elseif ($format === 'uk') {
        $parts = [
            $address->building_name ?: $address->building_number,
            $address->street_address,
            $address->address_line_2,
            $address->city,
            $address->county,
            $address->postal_code ?: $address->zip_code,
            addressCountryName($address->country_id),
        ];
    } elseif ($format === 'uae') {
        $parts = [
            $address->building_name ?: $address->building_number,
            $address->flat_unit,
            $address->street_address,
            $address->area,
            $address->city,
            $address->emirate,
            $address->p_o_box,
            addressCountryName($address->country_id),
        ];
    } else {
        $parts = [
            $address->address_line_1 ?: $address->address,
            $address->address_line_2,
            $address->city,
            $address->state_region ?: $address->state,
            $address->postal_code ?: $address->zip_code,
            addressCountryName($address->country_id),
        ];
    }

    $formatted = compactAddressParts($parts);
    return $formatted !== '' ? $formatted : '-';
}

/* Get full address of the user */
function fullAddress($type, $type_id)
{
    $types = array_unique([$type, strtolower($type), ucfirst(strtolower($type))]);
    // With multiple addresses per record, always resolve to the primary/current one.
    $address = Address::whereIn('type', $types)->where('type_id', $type_id)
        ->orderByDesc('is_primary')->first();
    return formattedAddress($address);
}

/* Get Inital Name of the user */
function initialName($user_type, $user_id)
{
    if ($user_type == 'Student') {
        $user = Student::find($user_id);
    } elseif ($user_type == 'Trainer') {
        $user = Trainer::find($user_id);
    }
    $first = $user->first_name;
    $last = $user->family_name;
    return $first[0] . $last[0];
}

/* Get Setting value by key */
function getSettingValue($key)
{
    $setting = Setting::where('key', $key)->first();
    if ($setting) {
        return $setting->value;
    } else {
        return NULL;
    }
}

/* Update Setting value */
function updateSettingValue($key, $value)
{
    $setting = Setting::where('key', $key)->first();
    if ($setting) {
        $setting->update(['value' => $value]);
    } else {
        Setting::create(['key' => $key, 'value' => $value]);
    }
}

/* Get Student ID field options */
function studentIdFieldOptions()
{
    return [
        'manual' => 'Manual',
        'automatic' => 'Automatic',
    ];
}

/* Get Student ID format options */
function studentIdFormatOptions()
{
    return [
        'none' => 'None',
        'prefix' => 'Prefix',
        'suffix' => 'Suffix',
        'both' => 'Both',
    ];
}

/* Get normalized Student ID settings */
function studentIdSettings()
{
    $field = strtolower((string) getSettingValue('student_id_field'));
    $format = strtolower((string) getSettingValue('student_id_format'));

    return [
        'field' => array_key_exists($field, studentIdFieldOptions()) ? $field : 'manual',
        'number_start' => max(1, (int) getSettingValue('student_id_number_start')),
        'format' => array_key_exists($format, studentIdFormatOptions()) ? $format : 'none',
        'prefix' => (string) getSettingValue('student_id_prefix'),
        'suffix' => (string) getSettingValue('student_id_suffix'),
    ];
}

/* Check if Student ID is automatic */
function studentIdIsAutomatic()
{
    return studentIdSettings()['field'] == 'automatic';
}

/* Build Student ID from the configured format */
function buildStudentId($number)
{
    $settings = studentIdSettings();
    $studentId = (string) $number;

    if ($settings['format'] == 'prefix' || $settings['format'] == 'both') {
        $studentId = $settings['prefix'] . $studentId;
    }

    if ($settings['format'] == 'suffix' || $settings['format'] == 'both') {
        $studentId .= $settings['suffix'];
    }

    return $studentId;
}

/* Reserve the next available automatic Student ID */
function reserveAutomaticStudentId()
{
    return DB::transaction(function () {
        $setting = Setting::where('key', 'student_id_number_start')->lockForUpdate()->first();

        if (!$setting) {
            $setting = Setting::create([
                'key' => 'student_id_number_start',
                'value' => 1,
            ]);
        }

        $number = max(1, (int) $setting->value);
        $studentId = buildStudentId($number);

        while (Student::where('id_no', $studentId)->exists()) {
            $number++;
            $studentId = buildStudentId($number);
        }

        $setting->update(['value' => $number + 1]);

        return $studentId;
    });
}

/* Get Teacher ID field options */
function teacherIdFieldOptions()
{
    return [
        'manual' => 'Manual',
        'automatic' => 'Automatic',
    ];
}

/* Get Teacher ID format options */
function teacherIdFormatOptions()
{
    return [
        'none' => 'None',
        'prefix' => 'Prefix',
        'suffix' => 'Suffix',
        'both' => 'Both',
    ];
}

/* Get normalized Teacher ID settings */
function teacherIdSettings()
{
    $field = strtolower((string) getSettingValue('teacher_id_field'));
    $format = strtolower((string) getSettingValue('teacher_id_format'));

    return [
        'field' => array_key_exists($field, teacherIdFieldOptions()) ? $field : 'manual',
        'number_start' => max(1, (int) getSettingValue('teacher_id_number_start')),
        'format' => array_key_exists($format, teacherIdFormatOptions()) ? $format : 'none',
        'prefix' => (string) getSettingValue('teacher_id_prefix'),
        'suffix' => (string) getSettingValue('teacher_id_suffix'),
    ];
}

/* Check if Teacher ID is automatic */
function teacherIdIsAutomatic()
{
    return teacherIdSettings()['field'] == 'automatic';
}

/* Build Teacher ID from the configured format */
function buildTeacherId($number)
{
    $settings = teacherIdSettings();
    $teacherId = (string) $number;

    if ($settings['format'] == 'prefix' || $settings['format'] == 'both') {
        $teacherId = $settings['prefix'] . $teacherId;
    }

    if ($settings['format'] == 'suffix' || $settings['format'] == 'both') {
        $teacherId .= $settings['suffix'];
    }

    return $teacherId;
}

/* Reserve the next available automatic Teacher ID */
function reserveAutomaticTeacherId()
{
    return DB::transaction(function () {
        $setting = Setting::where('key', 'teacher_id_number_start')->lockForUpdate()->first();

        if (!$setting) {
            $setting = Setting::create([
                'key' => 'teacher_id_number_start',
                'value' => 1,
            ]);
        }

        $number = max(1, (int) $setting->value);
        $teacherId = buildTeacherId($number);

        while (Trainer::where('id_no', $teacherId)->exists()) {
            $number++;
            $teacherId = buildTeacherId($number);
        }

        $setting->update(['value' => $number + 1]);

        return $teacherId;
    });
}

/* Get Logo */
function getLogo()
{
    $logo = getSettingValue('logo');
    if ($logo != NULL) {
        return $logo;
    } else {
        $company = Company::first();
        return $company->logo;
    }
}

/* Get Title */
function getTitle()
{
    $title = getSettingValue('title');
    if ($title != NULL) {
        return $title;
    } else {
        $company = Company::first();
        return $company->company_name;
    }
}

/* Get Fav Icon */
function getFavIcon()
{
    $favIcon = getSettingValue('fav_icon');
    if ($favIcon != NULL) {
        return $favIcon;
    } else {
        return 'fav.png';
    }
}

/* Get Footer */
function getFooter($param)
{
    if ($param == 'text') return 'Eloom Pty Ltd';
    else return 'http://eloom.com.au';
}

/* Get site-wide theme (driven by the first admin user's theme selection) */
function getSiteTheme()
{
    $user = User::first();
    return $user ? ($user->theme ?? 'light') : 'light';
}

/* Setting for dashboard widgets */
function dashboardWidget($key)
{
    $type = getSettingValue($key);
    if ($type == NULL) {
        return 'on';
    } else {
        return $type;
    }
}

/* Setting for assignment */
function assignmentSetting($key)
{
    $type = getSettingValue($key);
    if ($type == NULL) {
        return 'on';
    } else {
        return $type;
    }
}


/* Setting for fee */
function feeSetting($key)
{
    $type = getSettingValue($key);
    if ($type == NULL) {
        if ($key == 'fee_module') return 'yes';
        else return 'no';
    } else {
        return $type;
    }
}

/* Get date format from settings */
function dateFormat($date)
{
    $date_format = getSettingValue('date_format');
    if ($date_format != NULL) {
        return date($date_format, strtotime($date));
    } else {
        // Austrailian date format
        return date("d/m/Y", strtotime($date));
    }
}

/* Get time format from settings */
function timeFormat($time)
{
    $time_format = getSettingValue('time_format');
    if ($time_format != NULL) {
        return date($time_format, strtotime($time));
    } else {
        return date("h:i A", strtotime($time));
    }
}

/* Get full date and time format */
function dateTimeFormat($full_date)
{
    $date = dateFormat($full_date);
    $time = timeFormat($full_date);
    return $date . ' ' . $time;
}

/* Convert Date format for sorting */
function convertDate($date)
{
    return str_replace("-", "", $date);
}

/* Convert formatted date to non formatted date */
function getNonFormattedDate($formatted_date)
{
    $date_format = getSettingValue('date_format');
    if ($date_format == NULL) {
        // Austrailian date format
        $date_format = 'd/m/Y';
    }

    // Create a DateTime object by parsing the input date
    $dateObj = DateTime::createFromFormat($date_format, $formatted_date);

    // Check if the date parsing was successful
    if ($dateObj !== false) {
        // Convert the date to 'Y-m-d' format
        $formattedDate = $dateObj->format('Y-m-d');

        // Output the formatted date
        return $formattedDate;
    } else {
        return "";
    }
}

/* Deploying FCM */
function fcm($data)
{
    $fcm_key = getSettingValue('fcm_server_api_key');
    if ($fcm_key != NULL) {
        $SERVER_API_KEY = $fcm_key;
    } else {
        $SERVER_API_KEY = env("SERVER_API_KEY");
    }
    $dataString = json_encode($data);

    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    return $response;
}

function sendPushNotification($title, $body, $token, $clickAction = null)
{
    $fcm_sender_id =  getSettingValue('fcm_sender_id');
    $fcmUrl = 'https://fcm.googleapis.com/v1/projects/' . $fcm_sender_id . '/messages:send';
    $serverKey = getSettingValue('fcm_server_api_key');

    $payload = [
        'message' => [
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'sound' => 'default',
                    'click_action' => $clickAction,
                ],
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'sound' => 'default',
                        'category' => 'default',
                    ],
                ],
                'fcm_options' => [
                    'link' => $clickAction,
                ],
            ],
            'webpush' => [
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'click_action' => $clickAction,
                ],
            ],
            'data' => [
                'click_action' => $clickAction,
            ],
        ],
    ];

    $headers = [
        'Authorization' => 'Bearer ' . $serverKey,
        'Content-Type'  => 'application/json',
    ];

    $client = new Client();

    try {
        $response = $client->post($fcmUrl, [
            'headers' => $headers,
            'json'    => $payload,
        ]);

        return $response->getStatusCode() === 200;
    } catch (\Exception $e) {
        FacadeLog::error('FCM Notification Error: ' . $e->getMessage());
        return false;
    }
}

/* Get Notification of the user */
function notification($user_type)
{
    if ($user_type == 'Admin') {
        $user = 'user';
    } elseif ($user_type == 'Student') {
        $user = 'student';
    } elseif ($user_type == 'Student API') {
        $user = 'student_api';
        $user_type = 'Student';
    } elseif ($user_type == 'Trainer') {
        $user = 'trainer';
    }
    $user_id = Auth::guard($user)->user()->id;
    $notifications = Notification::where('user_type', $user_type)->where('user_id', $user_id)->get();
    return $notifications;
}

/* Generate Password Reset Code */
function randomResetCode($user_type)
{
    $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $uniqueCode = false;
    $code = 0;
    while ($uniqueCode == false) {
        $code = substr(str_shuffle($str), 0, 8);
        if ($user_type == 'Student') {
            $check = StudentPasswordReset::where('code', $code)->count();
        } elseif ($user_type == 'Trainer') {
            $check = TrainerPasswordReset::where('code', $code)->count();
        } else {
            $check = UserPasswordReset::where('code', $code)->count();
        }
        if ($check > 0) {
            $uniqueCode = false;
        } else {
            $uniqueCode = true;
        }
    }
    return $code;
}

/* Deploying Zoom Meeting */
function zoom($data)
{
    $dataString = json_encode($data);
    $url = asset('/api/meetings');

    $headers = [
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result;
}

/* Access for zoom recording */
function getZoomRecording($meeting_id)
{
    $zoom_api_url = getSettingValue('zoom_api_url');
    if ($zoom_api_url != NULL) {
        $zoom_url =  $zoom_api_url;
    } else {
        $zoom_url = env('ZOOM_API_URL', '');
    }

    $zoom_jwt = getSettingValue('zoom_api_jwt');
    if ($zoom_jwt != NULL) {
        $jwt =  $zoom_jwt;
    } else {
        $jwt = env('ZOOM_API_JWT', '');
    }

    $url = $zoom_url . 'meetings/' . $meeting_id . '/recordings?access_token=' . $jwt;
    try {
        $result = file_get_contents($url);
    } catch (Exception $e) {
        return false;
    }
    $json_data = json_decode($result);
    return $json_data;
}

/* Get extension and icon of files */
function filePath($filePath)
{
    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    $extenion = substr($ext, 0, 2);
    if ($extenion == 'do') $path = 'files/word.png';
    elseif ($extenion == 'pp') $path = 'files/ppt.png';
    elseif ($extenion == 'pd') $path = 'files/pdf.png';
    else {
        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $allowedVideoExtensions = ['mp4', 'avi', 'mov', 'mp3'];
        if (in_array(strtolower($ext), $allowedImageExtensions)) {
            $path = 'files/img.png';
        } elseif (in_array(strtolower($ext), $allowedVideoExtensions)) {
            $path = 'files/video.png';
        } else {
            $path = 'files/files.png';
        }
    }
    return $path;
}

/* Get count of days in a month by year */
function daysInMonth(int $year, int $month)
{
    return now()->setYear($year)
        ->setMonth($month)
        ->daysInMonth;
}

function studentPaymentStatus($status_code)
{
    if ($status_code == 1) $status = 'Remaining';
    elseif ($status_code == 2) $status = 'Paid';
    elseif ($status_code == 3) $status = 'Refunded';
    return $status;
}

/* Get Student Offer Number */
function offerNumber($id)
{
    $letter = StudentOffer::find($id);
    $agent = $letter->student->studentAgent;
    $date = date('dmY') . ' - ' . date('Hi');
    if ($agent) {
        $name = strtoupper(substr($agent->agent->company_name, 0, 3));
        $offer_number = $name . $date;
    } else {
        $offer_number = $date;
    }
    return $offer_number;
}

/* Generate array of hex colors */
function randomHexColor($n)
{
    $colors = [];

    while (count($colors) < $n) {
        // Generate a random color
        $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

        // Ensure the color is unique
        if (!isset($colors[$color])) {
            $colors[$color] = true;
        }
    }

    return array_keys($colors);
}

function acronym($text)
{
    $words = explode(" ", $text);
    return $words[0];
}

/* Adding space to data for nat report */
function natReport($field, $data)
{
    if ($data == NULL) {
        $data = '';
    }
    // dd($data, $field);
    if ($field == 'rto_no') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'name') {
        $data = preg_replace('/[^A-Za-z0-9 ]/', '', $data);
        $space_count = 100 - strlen($data);
    } elseif ($field == 'phone') {
        $space_count = 20 - strlen($data);
    } elseif ($field == 'fax') {
        $space_count = 20 - strlen($data);
    } elseif ($field == 'email') {
        $space_count = 80 - strlen($data);
    } elseif ($field == 'site_code') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'suburb') {
        $space_count = 50 - strlen($data);
    } elseif ($field == 'building_no') {
        $space_count = 50 - strlen($data);
    } elseif ($field == 'flat_unit') {
        $space_count = 30 - strlen($data);
    } elseif ($field == 'street_no') {
        $space_count = 15 - strlen($data);
    } elseif ($field == 'street_address') {
        $space_count = 70 - strlen($data);
    } elseif ($field == 'zip_code') {
        if (strlen($data) == 1) {
            return '000' . $data;
        } elseif (strlen($data) == 2) {
            return '00' . $data;
        } elseif (strlen($data) == 3) {
            return '0' . $data;
        } else {
            return $data;
        }
    } elseif ($field == 'survey_contact_status') {
        $space_count = 1 - strlen($data);
    } elseif ($field == 'course_code') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'hours') {
        if ($data == '' || $data == '0') {
            $data = '0000';
        }
        $space_count = 4 - strlen($data);
        return str_repeat('0', $space_count) . $data;
    } elseif ($field == 'unit_code') {
        $space_count = 12 - strlen($data);
    } elseif ($field == 'unit_education_field') {
        $space_count = 6 - strlen($data);
    } elseif ($field == 'student_id') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'student_name') {
        $space_count = 60 - strlen($data);
    } elseif ($field == 'student_titile') {
        $space_count = 4 - strlen($data);
    } elseif ($field == 'student_first_name' || $field == 'student_family_name') {
        $space_count = 40 - strlen($data);
    } elseif ($field == 'student_p_o_box') {
        $space_count = 22 - strlen($data);
    } elseif ($field == 'student_school_level') {
        if ($data == '') {
            $data = '@@';
        }
        $space_count = 2 - strlen($data);
    } elseif ($field == 'gender') {
        if ($data == '') {
            $data = 'X';
        }
        $space_count = 1 - strlen($data);
    } elseif ($field == 'unique_student_identifier') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'disability_identifier') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'prior_education_achievement_identifier') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'specific_funding_identifier') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'school_type_identifier') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'date') {
        if ($data != '') {
            $date = dateFormat($data);
            return str_replace(array('-', '/'), '', $date);
        } else {
            return '        ';
        }
    } elseif ($field == 'training_organisation_code') {
        $space_count = 10 - strlen($data);
    } elseif ($field == 'study_reason') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'state_funding') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'outcome') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'funding_source_national') {
        $space_count = 2 - strlen($data);
    } elseif ($field == 'parchment_no') {
        $space_count = 25 - strlen($data);
    } else {
        $space_count = 0;
    }
    return $data . str_repeat(' ', abs($space_count));
}

/* Dynamic email setting */
function sendEmail($id, $to_name, $to_email, $data, $type)
{
    $emailSettings = Email::find($id);
    if ($emailSettings) {
        $config = [
            'driver'     => 'smtp',
            'host'       => $emailSettings->host,
            'port'       => $emailSettings->port,
            'username'   => $emailSettings->username,
            'password'   => $emailSettings->password,
            'encryption' => $emailSettings->encryption,
            'port'       => $emailSettings->port,
            // Add other email configuration settings here
        ];

        // Set the email configuration dynamically
        Config::set('mail', $config);

        $from_address = $emailSettings->from_address;
        $from_name = $emailSettings->from_name;
        $reply_to = $emailSettings->reply_to;
        $subject = $data['subject'];

        if ($type == 'Student' || $type == 'Trainer' || $type == 'Agent' || $type == 'User') {
            Mail::send('student::email.template.email', $data, function ($message) use ($to_name, $to_email, $from_address, $from_name, $reply_to, $subject) {
                $message->to($to_email, $to_name)
                    ->subject($subject);
                $message->from($from_address, $from_name);
                $message->replyTo($reply_to);
            });
            return true;
        }
    }
}

/* Get value of student template data */
function getStudentTemplateDataValue($student_template_id, $key)
{
    $student_template_data = StudentTemplateData::where('student_template_id', $student_template_id)->where('key', $key)->first();
    if ($student_template_data) {
        return $student_template_data->value;
    } else {
        return '';
    }
}

/* Get Delivery Site List */
function getDeliverySites()
{
    $user = Auth::guard('user')->user();
    $user_id = $user->id;
    $role = $user->user_type;
    $user_delivery_sites = UserDeliverySite::where('user_id', $user_id)->where('status', 1)->get();
    if (count($user_delivery_sites) == 0 || $role == 'super_admin') {
        $sites = CompanyDeliverySite::where('status', 1)->get();
    } else {
        foreach ($user_delivery_sites as $key => $value) {
            $ids[] = $value->company_delivery_site_id;
        }
        $sites = CompanyDeliverySite::whereIn('id', $ids)->where('status', 1)->get();
    }
    return $sites;
}

/* Get Delivery Site Ids List */
function getDeliverySiteIds()
{
    $user = Auth::guard('user')->user();
    $user_id = $user->id;
    $role = $user->user_type;
    $user_delivery_sites = UserDeliverySite::where('user_id', $user_id)->where('status', 1)->get();
    if (count($user_delivery_sites) == 0 || $role == 'super_admin') {
        $sites = CompanyDeliverySite::where('status', 1)->get();
    } else {
        foreach ($user_delivery_sites as $key => $value) {
            $ids[] = $value->company_delivery_site_id;
        }
        $sites = CompanyDeliverySite::whereIn('id', $ids)->where('status', 1)->get();
    }
    foreach ($sites as $site) {
        $site_ids[] = $site->id;
    }
    return $site_ids;
}

/* Get Course Count By Intake */
function getCourseCountByIntake($intake_id)
{
    $ids = getDeliverySiteIds();
    $intakeCourseCount = IntakeCourse::join('courses', 'courses.id', '=', 'intake_courses.course_id')
        ->leftJoin('course_delivery_sites', 'course_delivery_sites.course_id', '=', 'courses.id')
        ->where(function ($query) use ($ids) {
            $query->whereNull('course_delivery_sites.course_id')
                ->orWhereIn('course_delivery_sites.company_delivery_site_id', $ids);
        })
        ->select('intake_courses.*')
        ->where('courses.status', 1)
        ->where('intake_courses.intake_id', $intake_id)
        ->count();
    return $intakeCourseCount;
}

/* Check for course delivery site */
function checkCourseDeliverySite($course_id)
{
    $delivery_site = CourseDeliverySite::where('course_id', $course_id)->first();
    if ($delivery_site) {
        $ids = getDeliverySiteIds();
        $sites = CourseDeliverySite::whereIn('company_delivery_site_id', $ids)->where('course_id', $course_id)->get();
        if (count($sites) == 0) $site = false;
        else $site = true;
    } else {
        $site = true;
    }
    return $site;
}

function getReportTemplateList()
{
    return ReportTemplate::where('status', 1)->get();
}

/* Get Country Id By Name */
function getCountryIdByName($name)
{
    $country = Country::where('name', $name)->first();
    return $country->id;
}

/* Get Phone Number */
function getPhoneNumber($phone)
{
    return str_replace(' ', '', $phone);
}

function getFullStateNamefromInitial($initial)
{
    $states = [
        'New South Wales' => 'NSW',
        'Victoria' => 'VIC',
        'Queensland' => 'QLD',
        'South Australia' => 'SA',
        'Western Australia' => 'WA',
        'Tasmania' => 'TAS',
        'Northern Territory' => 'NT',
        'Australian Capital Territory' => 'ACT'
    ];
    return array_search($initial, $states);
}


/* Get Country Value from nationality */
function getCountryFromNationality($nationality)
{
    $country = Country::where('nationality', 'LIKE', '%' . $nationality . '%')->first();
    return $country->id;
}

/* Dynamic email from setting chosen */
function sendEmailSetting()
{
    $emailSetting = getSettingValue('email_setting_type');
    if ($emailSetting == 'mailgun') {
        $config = [
            'driver'       => 'smtp',
            'host'         => getSettingValue('mailgun_mail_host'),
            'port'         => getSettingValue('mailgun_mail_port'),
            'username'     => getSettingValue('mailgun_mail_username'),
            'password'     => getSettingValue('mailgun_mail_password'),
            'encryption'   => getSettingValue('mailgun_mail_encryption'),
            'port'         => getSettingValue('mailgun_mail_port'),
            'from' => [
                'address'  => getSettingValue('mailgun_mail_from_address'),
                'name'     => getSettingValue('mailgun_mail_from_name'),
            ],
            'mailgun' => [
                'domain'   => getSettingValue('mailgun_domain'),
                'secret'   => getSettingValue('mailgun_secret'),
            ],
            // Add other email configuration settings here
        ];
        FacadesLog::info('Mail configuration', $config);
        Config::set('mail', $config);

        config([
            'services.mailgun.domain' => getSettingValue('mailgun_domain'),
            'services.mailgun.secret' => getSettingValue('mailgun_secret'),
        ]);
    } elseif ($emailSetting == 'mailchimp') {
        $config = [
            'driver'       => 'smtp',
            'host'         => getSettingValue('mailchimp_mail_host'),
            'port'         => getSettingValue('mailchimp_mail_port'),
            'username'     => getSettingValue('mailchimp_mail_username'),
            'password'     => getSettingValue('mailchimp_mail_password'),
            'encryption'   => getSettingValue('mailchimp_mail_encryption'),
            'port'         => getSettingValue('mailchimp_mail_port'),
            'from' => [
                'address'  => getSettingValue('mailchimp_mail_from_address'),
                'name'     => getSettingValue('mailchimp_mail_from_name'),
            ],
            // Add other email configuration settings here
        ];
        FacadesLog::info('Mail configuration', $config);
        Config::set('mail', $config);
    } elseif ($emailSetting == 'office365') {
        $config = [
            'driver'       => 'smtp',
            'host'         => getSettingValue('office365_mail_host'),
            'port'         => getSettingValue('office365_mail_port'),
            'username'     => getSettingValue('office365_mail_username'),
            'password'     => getSettingValue('office365_mail_password'),
            'encryption'   => getSettingValue('office365_mail_encryption'),
            'port'         => getSettingValue('office365_mail_port'),
            'from' => [
                'address'  => getSettingValue('office365_mail_from_address'),
                'name'     => getSettingValue('office365_mail_from_name'),
            ],
            // Add other email configuration settings here
        ];
        FacadesLog::info('Mail configuration', $config);
        Config::set('mail', $config);
    }
    return true;
}

/* Get Stripe Key/Secret */
function getStripeKey($type)
{
    if (getSettingValue($type) == NULL) {
        if ($type == 'stripe_key') {
            return env('STRIPE_KEY');
        } else {
            return env('STRIPE_SECRET');
        }
    } else {
        return getSettingValue($type);
    }
}

/* Get Locations of Database */
function getLocations()
{
    $locations = Location::where('status', 1)->where('location_id', 0)->pluck('name', 'id');
    return $locations;
}

/* Get Days by Dates */
function getDaysBetweenDates($startDate, $endDate, $day)
{
    $startDateTime = new DateTime($startDate);
    $endDateTime = new DateTime($endDate);
    $interval = new DateInterval('P1D'); // 1-day interval
    $dateRange = new DatePeriod($startDateTime, $interval, $endDateTime->modify('+1 day'));
    if ($day == 'Sunday') $phpDay = 0;
    elseif ($day == 'Monday') $phpDay = 1;
    elseif ($day == 'Tuesday') $phpDay = 2;
    elseif ($day == 'Wednesday') $phpDay = 3;
    elseif ($day == 'Thursday') $phpDay = 4;
    elseif ($day == 'Friday') $phpDay = 5;

    $dates = [];

    foreach ($dateRange as $date) {
        if ($date->format('w') == $phpDay) { // Sunday is represented by 0 in PHP
            $dates[] = $date->format('Y-m-d');
        }
    }

    return $dates;
}

function getTicketStatus($status)
{
    if ($status == 1) return 'Opened';
    elseif ($status == 2) return 'In Progress';
    elseif ($status == 3) return 'Closed';
    elseif ($status == 4) return 'Reopened';
}

function getChatStatus($status)
{
    if ($status == 1) return 'Active';
    elseif ($status == 2) return 'Deleted';
    elseif ($status == 0) return 'InActive';
}

/* Message Emitter to Socket Server */
function emitMessageToSocket($chatId, $userId, $userName, $userType, $userImage, $message, $messageType)
{
    try {
        $client = new Client();
        // $response = $client->post('http://127.0.0.1:3000/emit', [
        $response = $client->post('https://lmsnp.eloom.com.au:3000/emit', [
            'json' => [
                'chat_id' => $chatId,
                'user_id' => $userId,
                'user_name' => $userName,
                'user_type' => $userType,
                'user_image' => $userImage,
                'message' => $message,
                'messageType' => $messageType,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            FacadeLog::info('Message emitted successfully.');
        }
    } catch (\Exception $e) {
        FacadeLog::error('Error emitting message to socket server: ' . $e->getMessage());
    }
}

function renderTemplateZone($template, $zone, $placeholders = [])
{
    $layout = json_decode($template->layout, true);
    if (empty($layout[$zone])) return '';

    $html = '';
    foreach ($layout[$zone] as $el) {
        switch ($el['type']) {
            case 'logo':
                $align  = $el['align'] ?? 'left';
                $height = $el['height'] ?? 50;
                $html  .= '<div style="text-align:' . $align . ';margin:2px 0;"><img src="' . asset($template->logo) . '" style="height:' . $height . 'px;width:auto;max-width:100%;"></div>';
                break;
            case 'text':
                $content = nl2br(strtr(htmlspecialchars($el['content'] ?? ''), $placeholders));
                $style   = 'text-align:' . ($el['align'] ?? 'left') . ';'
                         . 'font-size:' . ($el['fontSize'] ?? 13) . 'px;'
                         . 'font-weight:' . ($el['fontWeight'] ?? 'normal') . ';'
                         . 'color:' . ($el['color'] ?? '#000000') . ';'
                         . 'margin:2px 0;';
                $html   .= '<div style="' . $style . '">' . $content . '</div>';
                break;
            case 'divider':
                $html .= '<hr style="border:none;border-top:' . ($el['thickness'] ?? 1) . 'px solid ' . ($el['color'] ?? '#cccccc') . ';margin:4px 0;">';
                break;
            case 'spacer':
                $html .= '<div style="height:' . ($el['height'] ?? 8) . 'px;"></div>';
                break;
        }
    }
    return $html;
}

function offerTemplateTokens($letter, $company)
{
    $student = $letter->student;
    $agent   = optional(optional($student->studentAgent)->agent);

    // First selected intake course (for the single-course "Course details" summary).
    $firstCourseId = explode(',', $letter->intake_course_ids)[0] ?? null;
    $ic = $firstCourseId ? \Modules\Intake\Entities\IntakeCourse::find($firstCourseId) : null;
    $courseName = $ic ? optional($ic->course)->course_name : '';
    $courseCode = $ic ? optional($ic->course)->course_code : '';
    $cricos     = $ic ? optional($ic->course)->cricos_code : '';

    // Per-student copy of the course detail fields (copied at assignment time).
    $sic = $firstCourseId
        ? \Modules\Student\Entities\StudentIntakeCourse::where('student_id', $letter->student_id)
            ->where('intake_course_id', $firstCourseId)->first()
        : null;

    return [
        // --- Student / applicant ---
        '{{student_name}}'           => userName('Student', $letter->student_id),
        '{{student_first_name}}'     => $student->first_name,
        '{{student_last_name}}'      => $student->family_name,
        '{{student_date_of_birth}}'  => dateFormat($student->date_of_birth),
        '{{student_gender}}'         => $student->gender,
        '{{student_nationality}}'    => $student->citizenship ?: optional($student->country)->name,
        '{{student_phone}}'          => $student->phone ?: $student->mobile,
        '{{student_email}}'          => $student->email,
        '{{passport_no}}'            => $student->passport_no,
        '{{date_of_birth}}'          => dateFormat($student->date_of_birth),
        '{{overseas_address}}'       => trim(($student->overseas_address ?? '') . ' ' . optional($student->overseasCountry)->name),
        '{{country}}'                => optional($student->overseasCountry)->name,

        // --- Offer ---
        '{{offer_number}}'           => offerNumber($letter->id),
        '{{issue_date}}'             => $letter->issue_date ? dateFormat($letter->issue_date) : dateFormat($letter->created_at),
        '{{expiry_date}}'            => $letter->expiry_date ? dateFormat($letter->expiry_date) : '',
        '{{condition_description}}'  => $letter->condition_description,
        '{{credit_description}}'     => $letter->credit_description,

        // --- Course (first selected course summary) ---
        '{{course_name}}'            => $courseName,
        '{{course_code}}'            => $courseCode,
        '{{cricos_code}}'            => $cricos ?: $company->rto_no,
        '{{course_start_date}}'      => $ic && $ic->starting_date ? dateFormat($ic->starting_date) : '',
        '{{course_end_date}}'        => $ic && $ic->ending_date ? dateFormat($ic->ending_date) : '',
        '{{course_duration}}'        => $ic ? $ic->duration : '',
        '{{course_weeks}}'           => $ic ? $ic->duration : '',

        // --- Organisation / settings ---
        '{{company_name}}'           => $company->company_name,
        '{{organisation_name}}'      => $company->company_name,
        '{{rto_code}}'               => $company->rto_no,
        '{{rto_no}}'                 => $company->rto_no,
        '{{organisation_email}}'     => getSettingValue('offer_organisation_email'),
        '{{organisation_phone}}'     => getSettingValue('offer_organisation_phone'),
        '{{signed_by_name}}'         => getSettingValue('offer_signed_by_name'),
        '{{signed_by_designation}}'  => getSettingValue('offer_signed_by_designation'),

        // --- Bank ---
        '{{bank_account_name}}'      => getSettingValue('offer_bank_account_name'),
        '{{bank_name}}'              => getSettingValue('offer_bank_name'),
        '{{bank_bsb}}'               => getSettingValue('offer_bank_bsb'),
        '{{bank_account_number}}'    => getSettingValue('offer_bank_account_number'),

        // --- Course details (per-student, from student_intake_courses) ---
        '{{study_mode}}'             => optional($sic)->study_mode,
        '{{study_location}}'         => optional($sic)->study_location,
        '{{work_placement}}'         => optional($sic)->work_placement,
        '{{hours_per_week}}'         => optional($sic)->hours_per_week,
        '{{holiday_breaks}}'         => optional($sic)->holiday_breaks,
        '{{entry_requirements}}'     => optional($sic)->entry_requirements,

        // --- Agent ---
        '{{agent_name}}'             => $agent->company_name,
        '{{agent_address}}'          => trim(($agent->address ?? '') . ' ' . ($agent->city ?? '')),
    ];
}

function renderOfferTemplate($template, $data)
{
    $layout = json_decode($template->layout, true);
    if (empty($layout) || !is_array($layout)) return '';

    $placeholders = $data['placeholders'] ?? [];
    $blocks = ['student_details', 'course_table', 'course_subjects', 'fees_overview', 'payment_schedule', 'fees_due', 'payment_plan', 'signature'];

    $html = '';
    foreach ($layout as $section) {
        $type = $section['type'] ?? '';
        if ($type === 'richtext') {
            $html .= '<div class="smallf" style="width:700px;">' . strtr($section['content'] ?? '', $placeholders) . '</div>';
        } elseif ($type === 'page_break') {
            $html .= '<div style="page-break-after: always;"></div>';
        } elseif (in_array($type, $blocks)) {
            $html .= '<div style="margin:6px 0;">' . view('student::offer-letter.blocks._' . $type, $data)->render() . '</div>';
        }
    }
    return $html;
}

function scholarshipInstallmentDiscounts($feeId)
{
    if (feeSetting('scholarship_module') != 'yes') return [];

    $fee = \Modules\Student\Entities\StudentIntakeCourseFee::with('feeDiscounts')->find($feeId);
    if (!$fee) return [];

    $totalDiscount = $fee->feeDiscounts->sum('discount_amount');
    if ($totalDiscount <= 0) return [];

    $unpaid = \Modules\Student\Entities\StudentIntakeCourseFeeInstallment::where('student_intake_course_fee_id', $feeId)
        ->where('parent_id', 0)
        ->where('status', 1)
        ->orderBy('due_date')
        ->get();

    $discounts = [];
    $remaining = $totalDiscount;
    foreach ($unpaid as $inst) {
        if ($remaining <= 0) break;
        $discount = min($inst->amount, $remaining);
        $discounts[$inst->id] = $discount;
        $remaining -= $discount;
    }
    return $discounts;
}
