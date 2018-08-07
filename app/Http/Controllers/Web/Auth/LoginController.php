<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Session;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        return view('auth.login');
    }

    public function loginProcess(Request $request){
        $client = new Client;
        $response = $client->request('POST', env('API_URL', 'http://sales-system.local/api/').env('API_VERSION', 'v1').'/login',[
            'query' => ['email' => $request->email, 'password' => $request->password],
        ]);

        $response = json_decode($response->getBody()->getContents());

        if($response->success){
            $responseData = $response->data;

            setcookie(env("TOKEN_COOKIE", 'phpsess'), $responseData->token->access_token, time() + $responseData->token->expires_in, '/', env('DOMAIN_COOKIE', ".sales-system.local"));

            Session::put('user', $responseData->user);

            return redirect('/');
        }
    }
}
