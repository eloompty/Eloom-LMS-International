<?php

namespace Modules\CRM\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Company\Entities\Company;
use Modules\CRM\Entities\ZohoLead;

class ZohoController extends Controller
{
    protected $client;
    protected $accessToken;
    protected $refreshToken;

    public function __construct()
    {
        $this->middleware('auth:user');
        $this->client = new Client();
    }

    public function redirectToZoho()
    {
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => getSettingValue('zoho_client_id'),
            'redirect_uri' => getSettingValue('zoho_redirect_uri'),
            'scope' => 'ZohoCRM.modules.leads.CREATE,ZohoCRM.modules.leads.READ,ZohoCRM.modules.leads.UPDATE,ZohoCRM.modules.leads.DELETE',
            'prompt' => 'consent',
            'access_type' => 'offline'
        ]);

        return redirect("https://accounts.zoho.com/oauth/v2/auth?$query");
    }

    public function handleZohoCallback(Request $request)
    {
        $code = $request->query('code');

        $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'grant_type' => 'authorization_code',
            'client_id' => getSettingValue('zoho_client_id'),
            'client_secret' => getSettingValue('zoho_client_secret'),
            'redirect_uri' => getSettingValue('zoho_redirect_uri'),
            'code' => $code,
        ]);

        $data = $response->json();
        if (isset($data['access_token'])) {
            // Save access token and refresh token to .env or database
            $accessToken = $data['access_token'];
            $refreshToken = $data['refresh_token'];

            // For simplicity, let's save to .env (in production, consider using database)
            $this->accessToken = $data['access_token'];
            $user_id = Auth::guard('user')->user()->id;
            ZohoLead::where('user_id', $user_id)->delete();
            ZohoLead::create(['user_id' => $user_id, 'token' => $accessToken, 'refresh_token' => $refreshToken]);
            return redirect()->route('admin.zoho.index')->with('success', 'Zoho connected successfully!');
        }

        return redirect()->route('admin.zoho.index')->with('failure', 'Failed to connect to Zoho.');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('zoho_lead', 'view') == true) {
            activityLog('Admin', 'Opened Zoho Lead List');
            $user_id = Auth::guard('user')->user()->id;
            $token = ZohoLead::where('user_id', $user_id)->first();
            if ($token) {
                try {
                    $response = $this->client->get('https://www.zohoapis.com/crm/v2/Leads', [
                        'headers' => [
                            'Authorization' => 'Zoho-oauthtoken ' . $token->token,
                        ],
                    ]);

                    $all_leads = json_decode($response->getBody(), true);
                    $leads = $all_leads['data'];
                    $lead_count = count($leads);
                    if (isset($all_leads['data'])) {
                        return view('crm::zoho.index', compact('leads', 'lead_count'));
                    }

                    return view('crm::zoho.index', ['failure' => 'Failed to retrieve leads.']);
                } catch (RequestException $e) {
                    if ($e->getResponse()->getStatusCode() == 401) {
                        return redirect()->route('admin.zoho.auth');
                    }

                    Log::error('Zoho API Request Error: ' . $e->getMessage());
                    return ['error' => 'Failed to retrieve leads.'];
                }
            } else {
                return redirect()->route('admin.zoho.auth');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('zoho_lead', 'add') == true) {
            activityLog('Admin', 'Opened create Lead Page');
            return view('crm::zoho.create');
        } else {
            return redirect()->route('admin.zoho.index')->with('failure', 'This user does not have permission to add Lead');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $user_id = Auth::guard('user')->user()->id;
            $token = ZohoLead::where('user_id', $user_id)->first();
            $data = $request->all();
            if (!isset($data['company']) || $data['company'] == NULL) {
                $company = Company::first();
                $company_name = $company->company_name;
            } else {
                $company_name = $data['company'];
            }
            $response = $this->client->request('POST', 'https://www.zohoapis.com/crm/v2/Leads', [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $token->token,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'data' => [
                        [
                            'Company' => $company_name,
                            'Last_Name' => $request->input('family_name'),
                            'First_Name' => $request->input('first_name'),
                            'Email' => $request->input('email'),
                            'phone' => $request->input('phone'),
                            'Phone' => $request->input('phone'),
                            'Lead_Status' => $request->lead_status
                        ]
                    ],
                    'trigger' => ['approval', 'workflow', 'blueprint']
                ]
            ]);

            if ($response->getStatusCode() == 201) {
                // return redirect()->back()->with('success', 'Lead created successfully!');
                return redirect()->route('admin.zoho.index')->with('success', 'Zoho lead has been added successfully');
            }

            return redirect()->back()->with('failure', 'Failed to create lead.');
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                return redirect()->route('admin.zoho.auth');
                // return $this->createLead($leadData);
            }
            // Handle other exceptions
            return ['error' => 'Failed to create lead.', $e];
            // Handle other exceptions
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('crm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('crm::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (checkRole('zoho_lead', 'delete') == true) {
            try {
                $user_id = Auth::guard('user')->user()->id;
                $token = ZohoLead::where('user_id', $user_id)->first();
                $response = $this->client->delete("https://www.zohoapis.com/crm/v2/Leads/{$id}", [
                    'headers' => [
                        'Authorization' => 'Zoho-oauthtoken ' . $token->token,
                    ],
                ]);

                // return json_decode($response->getBody(), true);
                $response = json_decode($response->getBody(), true);

                if (isset($response['data'][0]['code']) && $response['data'][0]['code'] == 'SUCCESS') {
                    return redirect()->route('admin.zoho.index')->with('success', 'Zoho Lead deleted successfully!');
                }

                return redirect()->route('admin.zoho.index')->with('error', 'Failed to delete lead.');
            } catch (RequestException $e) {
                if ($e->getResponse()->getStatusCode() == 401) {
                    return redirect()->route('admin.zoho.auth');
                }

                Log::error('Zoho API Request Error: ' . $e->getMessage());
                return ['error' => 'Failed to delete lead.'];
            }
        } else {
            return abort(404);
        }
    }
}
