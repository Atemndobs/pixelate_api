<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Events\Verified;
// use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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
     *
     * @OA\Get (
     * path="/api/verification/verify/{user}",
     * summary="Verify user ",
     * description="Verify user after Registration  using email",
     * tags={"Auth"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="object", ref="#/components/schemas/User"
     *         )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/Design")
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example="The given data was invalid."),
     *          @OA\Property(property="errors", type="object",
     *              @OA\Property(property="image",
     *                  example={"The image field is required."}
     *              ),
     *          ),
     *      ),
     *    ),
     * )
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function verify(Request $request, User $user): JsonResponse
    {
        //check if URL is a valid signed url
        if (!URL::hasValidSignature($request)){
            return response()->json(["errors" => [
                "message" => "Invalid verification link or signature"
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

        return response()->json(["message" => "email successfully verified"], 200);
    }

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
