<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected UserRepositoryInterface $userRepository;

    /**
     * RegisterController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:15','alpha_dash', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        return $this->userRepository->create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Register",
     * description="Register new user",
     * tags={"Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password", "username", "name", "password_confirmation"},
     *       @OA\Property(property="username", type="string", format="password", example="bezos"),
     *       @OA\Property(property="name", type="string", format="password", example="Amin Besoz"),
     *       @OA\Property(property="email", type="string", format="email", example="besoz256@email.com"),
     *       @OA\Property(property="password", type="string", format="password", example="pass1234"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="pass1234"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Amin Besoz"),
     *       @OA\Property(property="username", type="string", example="bezos"),
     *       @OA\Property(property="email", type="string", example="besoz256@email.com"),
     *       @OA\Property(property="id", type="number", example=25),
     *       @OA\Property(property="updated_at", type="string", example="2020-11-27T08:16:31.000000Z"),
     *       @OA\Property(property="created_at", type="string", example="2020-11-27T08:16:31.000000Z"),
     *       @OA\Property(property="photo_url", type="string", example="https://www.gravatar.com/avatar/a9d770a5af9a113e77c887c0b772d98fjpg?s=200&d=mm"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example="The given data was invalid."),
     *      @OA\Property(property="errors", type="object",
     *          @OA\Property(property="username",
     *              example={"The username has already been taken."}
     *          ),
     *          @OA\Property(property="email",
     *              example={"The email has already been taken."}
     *          ),
     *      ),
     *
     *        )
     *     ),
     * @OA\Response(
     *    response=409,
     *    description="Already Registered",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Please confirm your email")
     *        )
     *     )
     * ),
     */
    protected function registered(Request $request, $user)
    {
        if (response()->status() === 503){
            return json_encode(response()->getContent(), JSON_THROW_ON_ERROR);
        }
       return response()->json($user, 200);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        try {
            event(new Registered($user = $this->create($request->all())));
        }catch (\Exception $exception){
            return json_encode($exception->getMessage(), JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $e->getMessage();
        }

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }
}
