<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Passport\TokenRepository;
use App\Http\Middleware\RevokeTokenIfOwner;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware(['auth:api', 'scope:normal-login'])->only(['user','generate_api_key']);
        $this->middleware([RevokeTokenIfOwner::class])->only('revokeToken');
    }
    public function login (Request $request) {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (\Hash::check($request->password, $user->password)) {
                $token = $user->createToken('login', ['normal-login']);
                $success['status'] = "Success";
                $success['type'] = "Bearer";
                $success['access_token'] =  $token->accessToken;
                $success['access_token_expiration'] =  \Carbon\Carbon::create($token->token->expires_at);
                $success['user_data'] =  $user;
                return response()->json($success, 200);
            } else {
                $error = array(
                    'message'   => __('Unauthorized'),
                    'status'    => 401,
                );
                return response()->json($error, 401);
            }
        }
        else{
            $error = array(
                'message'   => __('Unauthorized'),
                'status'    => 401,
            );
            return response()->json($error, 401);
        }
    }
    /**
     * POST - /auth/register
     *
     * register Route
     * User can register new account
     * <aside class="notice">Accept: Application/json <br> content-type: Application/json</aside>
     * @bodyParam name string required the name of the user . Example: John Doe
     * @bodyParam email string required The email of the user . Example: user@example.com
     * @bodyParam phone string required The phone of the user . Example: 015555555555
     * @bodyParam password string required the password of the user . Example: password
     * @bodyParam password_confirmation string required the password of the user . Example: password
     * @tag Authentication
     */
    public function register(Request $request) {
        $validation = $request->validate([
            'email'    => 'required|unique:users,email',
            'name'     => 'required',
            'password' => 'required|confirmed'
        ]);
        $input = $request->all();
        $user = User::create($input);
        $success = array();
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['data'] =  $user;
        return response()->json($success, 201);
    }

    public function user()
    {
        $response = \Auth::user();
        // $response = \Auth::user()->tokens;
        // $response = collect($response)->where('revoked', false);
        return response()->json($response, 200);
    }

    public function revokeToken($token_id) {
        $tokenRepository = app(TokenRepository::class);
        $token = $tokenRepository->find($token_id);
        if($token->revoked) {
            return response()->json([
                'message' => 'token already revoked'
            ], 403);
        }
        $tokenRepository->revokeAccessToken($token_id);
        return response()->json([
            'message' => 'token successfully revoked'
        ], 200);
    }

    public function generate_api_key () {
        $user = \Auth::user();
        $token = $user->createToken('apiConsumers', ['get-protected']);
        return response()->json([
            $token
        ], 200);
    }
}

