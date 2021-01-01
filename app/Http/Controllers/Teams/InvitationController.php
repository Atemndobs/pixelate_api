<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvitationResource;
use App\Mail\SendInvitationToJoinTeam;
use App\Models\Invitation;
use App\Models\Team;
use App\Repositories\Contracts\InvitationRepositoryInterface;
use App\Repositories\Contracts\TeamRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    /**
     * @var InvitationRepositoryInterface
     */
    protected InvitationRepositoryInterface $invitationRepository;
    /**
     * @var TeamRepositoryInterface
     */
    private TeamRepositoryInterface $teamRepository;
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * InvitationController constructor.
     * @param InvitationRepositoryInterface $invitationRepository
     * @param TeamRepositoryInterface $teamRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(InvitationRepositoryInterface $invitationRepository,
                                TeamRepositoryInterface $teamRepository,
                                UserRepositoryInterface $userRepository)
    {
        $this->invitationRepository = $invitationRepository;
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $invitations = $this->invitationRepository->withCriteria([
            new LatestFirst(),
            new EagerLoad(['team'])
        ])->all();
        return InvitationResource::collection($invitations);
    }


    public function invite(Request $request, $teamId)
    {
        // get the team

        $team = $this->teamRepository->find($teamId);

        $this->validate($request, [
           'email' => 'required|email'
        ]);

        $user = auth()->user();

        // check if user Owns the Team
        if (!$user->isOwnerOfTeam($this->teamRepository->find($teamId))){
            return response()->json(['email' => 'You are not the team owner'], 401);
        }

        // check if the email has a pending invitation

        if ($team->hasPendingInvite($request->email)){
            return response()->json(['email' => 'Email already has pending invitation'], 422);
        }

        // get the recipient by email

        $recipient = $this->userRepository->findByEmail($request->email);

        //In no recipient exists, send  invitation to join the team.

        if (!$recipient){
            $this->createInvitation(false, $team, $request);
            return response()->json(['email' =>  'Invitation successfully sent'], 200);
        }

        // check if the team already has the user

        if ($team->hasUser($recipient)){
            return response()->json(['email' =>  `This user is already a team member`], 422);
        }

        // send the invitation to the user
        $this->createInvitation(true, $team, $request);
        return response()->json(['email' =>  'Invitation successfully sent '], 200);
    }


    public function resend(int $id)
    {
        $invitation = $this->invitationRepository->find($id);
//        if (!auth()->user()->isOwnerOfTeam($invitation->team)){
//            return response()->json(['email' => 'You are not the team owner'], 401);
//        }

        $this->authorize('resend', $invitation);

        $recipient = $this->userRepository->findByEmail($invitation->recipient_email);
        \Mail::to($invitation->recipient_email)
            ->send(new SendInvitationToJoinTeam($invitation, !is_null($recipient)));
        return response()->json(['email' =>  'Invitation resent'], 200);
    }

    public function respond(Request $request, int $id)
    {
        $this->validate($request, [
           'token' => 'required',
            'decision' => 'required'
        ]);

        $token = $request->token;
        $decision = $request->decision; // accept / deny
        $invitation = $this->invitationRepository->find($id);

        // check if recipient belongs to user

        $this->authorize('respond', $invitation);

        // check  to make sure that the tokens match

        if ($invitation->token !== $token){
            return response()->json(['email' => 'Invalid token'], 401);
        }

        // check if accepted
        if ($decision !== 'deny'){
            $this->invitationRepository->addUserToTeam($invitation->team, auth()->id());
        }

        $invitation->delete();
        return response()->json(['email' => 'Successful'], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Invitation $invitation
     * @return void
     */
    public function show(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Invitation $invitation
     * @return void
     */
    public function update(Request $request, Invitation $invitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy($id)
    {
        $invitation = $this->invitationRepository->find( $id);
        $this->authorize('delete', $invitation);
        $invitation->delete();
        return response()->json(['message: ' => 'Invitation successfully deleted'], 200);
    }

    /**
     * @param $user_exists
     * @param Team $team
     * @param $request
     */
    protected function createInvitation($user_exists, Team $team, $request): void
    {
        // dd($request->email);
        $invitation = $this->invitationRepository->create([
            'team_id' => $team->id,
            'sender_id' => auth()->id(),
            'recipient_email' => $request->email,
            'token' => md5(uniqid(microtime()))
        ]);
        \Mail::to($request->email)
            ->send(new SendInvitationToJoinTeam($invitation, $user_exists));
    }
}
