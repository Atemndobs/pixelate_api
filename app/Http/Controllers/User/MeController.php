<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

/**
 * Class MeController
 *
 * @OA\Schema(
 *     schema="UserProfile",
 *      allOf={
 *         @OA\Schema(ref="#/components/schemas/User"),
 *      }
 * )
 *
 * @OA\Get(
 *     path="/api/me",
 *     summary="Retrieve profile information",
 *     description="Get profile short information.** Reqiures Authorisation: Add Auth heather by clicking the Lock icon above",
 *     tags={"User Profile"},
 *     security={ {"token": {} }},
 *
 *      @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *            @OA\Property(property="data", type="object", ref="#/components/schemas/UserProfile")
 *             )
 *          ),
 *      @OA\Response(
 *         response=401,
 *         description="User should be authorized to get profile information",
 *         @OA\JsonContent(
 *            @OA\Property(property="message", type="string", example="Not authorized"),
 *         )
 *      )
 * )
 *
 * @package App\Http\Controllers\User
 */
class MeController extends Controller
{
    public function getMe()
    {

        if (auth()->check()) {
            return responder()->success(auth()->user(), UserTransformer::class);
        }
        return response()->json(null, 401);
    }
}
