<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Events\Verified;
// use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

   // use VerifiesEmails;

    protected UserRepositoryInterface $userRepository;

    /**
     * VerificationController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse | RedirectResponse
     */
    public function verify(Request $request, User $user): JsonResponse
    {
        //check if URL is a valid signed url
        URL::forceScheme('https');
        if (!URL::hasValidSignature($request)){

            return response()->json(["errors" => [
                "message" => "Invalid verification link or signature",
                "url" => URL::full(),
            ]], 422);
        }

        // check if the user has already verified account
        if ($user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "message" => "Email address already verified"
            ]], 422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        $clientUrl = env('CLIENT_URL');
        return response()->json([
            "message" => "email successfully verified",
            "return to app" => $clientUrl
        ], 200);
    }

    /**
     * @OA\Post(
     * path="/api/verification/resend",
     * summary="Resend Verification link",
     * description="Resend verification link for given email",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Resend verification link for given email",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="bamarktfact@gmail.com"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="verification link resent"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Results was not found in Database")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Email address is not registered.")
     *        )
     *     ),
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

        $user = $this->userRepository->findWhereFirst('email',$request->email);

        // $user = User::where('email', $request->email)->first();
        if (!$user){
            return response()->json(["errors" => [
                "email" => "No user has be found with this email address"
            ]], 422);
        }
        $user->sendEmailVerificationNotification();

        return \response()->json(['status' => 'verification link resent']);
    }
}
