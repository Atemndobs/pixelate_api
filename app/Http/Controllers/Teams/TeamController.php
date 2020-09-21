<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Repositories\Contracts\InvitationRepositoryInterface;
use App\Repositories\Contracts\TeamRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TeamController extends Controller
{
    /**
     * @var TeamRepositoryInterface
     */
    protected TeamRepositoryInterface $teamRepository;
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;
    /**
     * @var InvitationRepositoryInterface
     */
    private InvitationRepositoryInterface $invitationRepository;

    /**
     * TeamController constructor.
     * @param TeamRepositoryInterface $teamRepository
     * @param UserRepositoryInterface $userRepository
     * @param InvitationRepositoryInterface $invitationRepository
     */
    public function __construct(TeamRepositoryInterface $teamRepository,
                                UserRepositoryInterface $userRepository,
                                InvitationRepositoryInterface $invitationRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
        $this->invitationRepository = $invitationRepository;
    }

    /**
     * @param Request $request
     * @return TeamResource
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' =>'required|string|max:80|unique:teams,name'
        ]);

        // crete team in database
        $team = $this->teamRepository->create([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        // current user is inserted as team member using boot method in Team model

        return new TeamResource($team);
    }



    public function fetchUserTeams()
    {
        $teams = $this->teamRepository->fetchUserTeams();

       return TeamResource::collection($teams);
    }

    public function findById(int $id)
    {

        $team = $this->teamRepository->find($id);
        return new TeamResource($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return TeamResource
     */
    public function update(Request $request, int $id)
    {
        $team = $this->teamRepository->find($id);
        $this->authorize('update', $team);


       $this->validate($request, [
            'name' => ['required','string','max:80','unique:teams,name,'.$id]
        ]);

        $team = $this->teamRepository->update($id, [
           'name' => $request->name,
           'slug'=> Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id)
    {
        $team = $this->teamRepository->find($id);
        $this->authorize('delete', $team);

        $this->teamRepository->delete($id);

        return response()->json(["message" => "Record Deleted"], 200);
    }

    public function removeFromTeam($team_id, $user_id)
    {

        $team = $this->teamRepository->find($team_id);
        $user = $this->userRepository->find($user_id);


        // check that user is not owner

        if ($user->isOwnerOfTeam($team)){
            return response()->json(["message" => "You are Team Owner and can only delete yourself last"], 401);
        }

        // check the request sender is either owner of team or someone leaving the team

        dd($team->owner_id, auth()->id(), $user_id, $user->id);
        if (auth()->user()->isOwnerOfTeam($team) && auth()->id() !== $user->id) {
            return response()->json(["message" => "You are not allowed to do this"], 401);
        }

        $this->invitationRepository->removeUserFromTeam($team, $user_id);
        return response()->json(["message" => "Success"], 200);
    }
}
