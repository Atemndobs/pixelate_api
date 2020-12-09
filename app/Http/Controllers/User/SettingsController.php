<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Rules\CheckSamePassword;
use App\Rules\MatchOldPassword;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Update User Profile
     * PUT/PATCH /settings/profile
     *
     *
     * @OA\Put(
     * path="/api/settings/profile",
     * summary="Update Uder Profile",
     * description="Update User Detailed info",
     * tags={"User Profile"},
     * security={ {"token": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass User data",
     *    @OA\JsonContent(
     *      @OA\Property(property="name", type="string", example="Atem Ndobs"),
     *      @OA\Property(property="tagline:", type="string", maxLength=32, example="Producer:"),
     *      @OA\Property(property="location", type="object",
     *          @OA\Property(property="type",type="string",example="point"),
     *          @OA\Property(property="coordinates",
     *              example={8.503972,51.017243}
     *          ),
     *      ),
     *      @OA\Property(property="formatted_address", example="811 Sibyl Bypass Suite 783\n New Rita, AL 48220-0930" ),
     *      @OA\Property(property="available_to_hire", type="boolean", example=1 ),
     *      @OA\Property(property="about", type="string", example="VERY deeply with a soldier on each." ),
     *    ),
     * ),
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *            @OA\Property(property="data", type="object", ref="#/components/schemas/UserProfile")
     *             )
     *          ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="false"),
     *       @OA\Property(property="message", type="string", example="Post not found"),
     *     )
     *     )
     * )
     *
     * @param Request $request
     * @return UserResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

            $this->validate($request, [
                'name' => ['required'],
                'tagline' => ['required'],
                'formatted_address' => ['required'],
                'about' => ['required', 'string', 'min:20'],
                'available_to_hire' => ['required'],
                'location.latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
                'location.longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            ]);


        $points = new Point($request->location['latitude'], $request->location['longitude']);


        $lat = $points->getLat();
        $long = $points->getLng();
        $location = "ST_GeomFromText('POINT($lat $long)')";

        $user->update([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'formatted_address' => $request->formatted_address,
            'about' => $request->about,
            'available_to_hire' => $request->available_to_hire,
            'location' => DB::raw($location)
        ]);

        $query = "SELECT ST_AsGeoJSON(location) as location FROM `users` where `users`.id = $user->id ";

        $updated_location = DB::select($query);

        $updated_user = new UserResource($user);
        $updated_user->location = json_decode($updated_location[0]->location);

        return $updated_user;
    }

    public function updatePassword(Request $request)
    {
        //get current password, new password and password confirmation

        $this->validate($request, [
            'current_password' => ['required', new MatchOldPassword()],
            'password' =>['required', 'confirmed', 'min:6', new CheckSamePassword()]
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['message' => 'Password Updated'], 200);
    }

    /**
     * @OA\Delete (
     * path="/api/settings/user/{email}",
     * summary="Delete User",
     * description="Delete A User by email",
     * tags={"User Profile"},
     * security={ {"token": {} }},
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         @OA\Schema(
     *             type="string", example="bamarktfact@gmail.com"
     *         )
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass User data",
     *    @OA\JsonContent(
     *       @OA\Property(property="email", type="string", example="bamarktfact@gmail.com"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User successfully deleted"),
     *       @OA\Property(property="user", type="string", example="bamarktfact@gmail.com"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Not Found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User Already Deleted"),
     *     )
     *   )
     * )
     *
     */
    public function deleteUser($email)
    {
        $user = User::where('email', $email)->delete();

   //     return $user;

        if ($user === 0){
            return response(
                [
                    'message' => 'User Already Deleted',
                ],
                404
            );
        }

        return response(
            [
                'message' => 'User successfully deleted',
                'user' => $email
            ],
            200
        );

    }
}
