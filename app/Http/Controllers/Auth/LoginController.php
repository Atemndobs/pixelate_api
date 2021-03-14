<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;

/**
 * @OA\Post(
 * path="/api/login",
 * summary="Sign in",
 * description="Login by email, password",
 * tags={"Auth"},
 * security={ {"token": {} }},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="bamarktfact@gmail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="pass1234"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Success",
 *    @OA\JsonContent(
 *       @OA\Property(property="token", type="string", format="email", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTYwNTM0Nzc0OSwiZXhwIjoxNjA1MzUxMzQ5LCJuYmYiOjE2MDUzNDc3NDksImp0aSI6IlJGUmJtRVdiN244WnlTaWsiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.VZq7nu9rkkHRXHcZB68v1vMpnRoMBJGmABElufaVrtk"),
 *       @OA\Property(property="token_type", type="string", example="bearer"),
 *       @OA\Property(property="expires_in", type="number", example=1605351349),
 *    ),
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="message",
 *     type="string",
 *     example="Sorry, wrong email address or password. Please try again")
 *        )
 *     )
 * )
 */
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

        if (!$token) {
            return false;
        }

        // get the authenticated user
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
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
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return response()->json(["errors" => [
                "message" => "You need to verify this email account"
            ]], 422);
        }
        throw ValidationException::withMessages([
            $this->username() => "Authentication failed : Invalid credentials"
        ]);
    }


    /**
     * @OA\Post(
     * path="/api/logout",
     * summary="Logout",
     * description="Logout user and invalidate token",
     * tags={"Auth"},
     * security={ {"token": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Logs out logged in user",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Logged out successfully"),
     *    )
     * )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            $this->guard()->logout();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
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
