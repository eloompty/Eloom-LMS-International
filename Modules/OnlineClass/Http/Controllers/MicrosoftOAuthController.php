<?php

namespace Modules\OnlineClass\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Intake\Entities\IntakeUnit;

class MicrosoftOAuthController extends Controller
{
    public function redirect($id, $type)
    {
        $clientId = getSettingValue('microsoft_client_id');
        $redirectUri = getSettingValue('microsoft_redirect_uri');
        $tenant_url = getSettingValue('microsoft_tenant_url');
        $scopes = 'User.Read Mail.Read Calendars.ReadWrite offline_access';

        $authUrl = "https://login.microsoftonline.com/{$tenant_url}/oauth2/v2.0/authorize?client_id={$clientId}&response_type=code&redirect_uri={$redirectUri}&response_mode=query&scope={$scopes}";

        session(['id' => $id, 'type' => $type]);
        return redirect($authUrl);
    }

    public function callback(Request $request)
    {
        $code = $request->query('code');
        $clientId = getSettingValue('microsoft_client_id');
        $clientSecret = getSettingValue('microsoft_client_secret');
        $tenant_url = getSettingValue('microsoft_tenant_url');
        $redirectUri = getSettingValue('microsoft_redirect_uri');

        $tokenRequestUrl = "https://login.microsoftonline.com/{$tenant_url}/oauth2/v2.0/token";
        $response = \Http::asForm()->post($tokenRequestUrl, [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ]);

        $accessToken = $response->json()['access_token'];

        $id = session('id');
        $type = session('type');

        // Store the access token in the session or database
        session(['microsoft_access_token' => $accessToken, 'id' => $id, 'type' => $type]);

        if ($type == 'group') {
            return view('onlineclass::group.teams.create');
        } elseif ($type == 'intake_unit') {
            return view('intake::course.unit.teams.create');
        } elseif ($type == 'trainer') {
            return view('trainer::trainer.unit.teams.create');
        } elseif ($type == 'trainer_group') {
            return view('trainer::trainer.onlinegroup.teams.create');
        }

    }
}
