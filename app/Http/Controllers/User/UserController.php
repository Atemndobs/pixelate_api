<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * UserController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function index()
    {
        $users = $this->userRepository->withCriteria([
            new EagerLoad(['designs'])
        ])
        ->all();
        return UserResource::collection($users);
    }

    public function findUser($id)
    {
        $user= $this->userRepository->find($id);
        return new UserResource($user);
    }

    public function search(Request $request)
    {
        $designers = $this->userRepository->search($request);

        $all_results = [];
        foreach ($designers as $designer){
            $all_results[] = $designer[0];
        }
      return response()->json(['data' => $all_results], 200);
    }

    public function findByUserName($username)
    {
        $user = $this->userRepository->findWhereFirst('username', $username);

        return new UserResource($user);
    }
}
