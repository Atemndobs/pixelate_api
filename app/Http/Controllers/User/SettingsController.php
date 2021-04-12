<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Rules\CheckSamePassword;
use App\Rules\MatchOldPassword;
use App\Transformers\UserTransformer;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class SettingsController extends Controller
{
    /**
     * @var Request
     */
    public Request $request;

    public UserRepositoryInterface $userRepository;

    /**
     * SettingsController constructor.
     * @param Request $request
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(Request $request, UserRepositoryInterface $userRepository)
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
    }


    /**
     * Update User Profile
     * PUT/PATCH /settings/profile
     *
     *
     * @OA\Put(
     * path="/api/settings/profile",
     * summary="Update User Profile",
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
     * @throws ValidationException
     */
    public function updateProfile()
    {
        $request = $this->request;
        $user = auth()->user();

            $this->validate($request, [
                'name' => ['required'],
               // 'tagline' => ['required'],
                'formatted_address' => ['required'],
                'about' => ['required', 'string', 'min:20'],
                'available_to_hire' => ['required'],
                'location.latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
                'location.longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            ]);


        $points = new Point($request->location['latitude'], $request->location['longitude']);

        $lat = $points->getLat();
        $long = $points->getLng();
        $location = "ST_GeomFromText('POINT($long $lat)')";

        $user->update([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'formatted_address' => $request->formatted_address,
            'username' => $request->username,
            'about' => $request->about,
            'available_to_hire' => $request->available_to_hire ==='Yes'? 1 : 0,
            'location' => DB::raw($location)
        ]);
/*      $query = "SELECT ST_AsGeoJSON(location) as location FROM `users` where `users`.id = $user->id ";
        $updated_location = DB::select($query);
        $updated_user = new UserResource($user);
        $updated_user->location = json_decode($updated_location[0]->location);
*/

        $updated = $this->userRepository->find(auth()->id());
        return responder()->success($updated, UserTransformer::class);
    }

    /**
     * @return JsonResponse
     * @throws ValidationException
     */
    public function updatePassword(): JsonResponse
    {
        //get current password, new password and password confirmation

        $request = $this->request;
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
     * Update User Profile
     * POST /settings/profile
     * @OA\Post(
     * path="/api/avatar",
     * summary="Update User Profile Picture (Avatar)",
     * description="Update User Avatar",
     * tags={"User Profile"},
     * security={ {"token": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass User data",
     *    @OA\JsonContent(
     *      @OA\Property(property="about", type="string", example="VERY deeply with a soldier on each." ),
     *    ),
     * ),
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *      @OA\Property(property="about", type="string", example="VERY deeply with a soldier on each." ),
     *             )
     *          ),
     * @OA\Response(
     *    response=422,
     *    description="Upload failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Upload failed"),
     *     )
     *     )
     * )
     * @throws InvalidManipulation
     */
    public function updateAvatar()
    {

        $request = $this->request;
        $imageUrl = $request->file('image')->store('/avatars', 'public');
        $this->processImage($imageUrl);
        $user = User::find(auth()->id());
        $user->avatar = asset('storage/'.$imageUrl);
        $user->save();


        return response([
            'avatar' => $user->avatar
        ], 200);
    }

    public function deleteAvatar()
    {
        return responder()->success();
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

        if ($user === 0) {
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

    /**
     * @param $imageUrl
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function processImage($imageUrl): void
    {
        Image::load('storage/'.$imageUrl)
            ->width(100)
            ->height(100)
            ->crop(Manipulations::CROP_TOP_RIGHT, 100, 100)
            ->save();
    }
}
