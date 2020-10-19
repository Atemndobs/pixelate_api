<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


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

    public function __construct()
    {
        auth()->setDefaultDriver('api');
    }


    public function attemptLogin(Request $request)
    {
        // attempt to issue a token to the user based on their credentials

        $token = $this->guard()->attempt($this->credentials($request));

        if (!$token){
            return false;
        }

        // get the authenticated user
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail())
        {
            return false;
        }

        //set the Users token

       $this->guard()->setToken($token);
        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        //get the Token from registration guard (the JWT guard)
        $token = $this->guard()->getToken()->get('value');

        // extract the expiry date of the token
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);
    }


    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->user();
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "message" => "You need to verify this email account"
            ]], 422);
        }
        throw ValidationException::withMessages([
            $this->username() => "Authentication failed : Invalid credentials"
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function getMe()
    {
        if (auth()->check()) {
            $user = auth()->user();
            return new UserResource($user);
        }
        return response()->json(null, 401);
    }
}
