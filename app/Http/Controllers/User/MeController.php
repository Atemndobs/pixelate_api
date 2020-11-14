<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="UserProfile",
     *      allOf={
     *         @OA\Schema(ref="#/components/schemas/User"),
     *      }
     * )
     *
     * @OA\Get(
     *      path="/api/me",
     *      summary="Retrieve profile information",
     *      description="Get profile short information",
     *      operationId="profileShow",
     *      tags={"User Profile"},
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMe()
    {
        dd('JHH');
        if (auth()->check()) {
            return response()->json(["data" => auth()->user()], 200);
        }
        return response()->json(null, 401);
    }
}
