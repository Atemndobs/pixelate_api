<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function view(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function resend(User $user, Invitation $invitation)
    {
        return $user->id == $invitation->sender_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function delete(User $user, Invitation $invitation)
    {
        return $user->id == $invitation->sender_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function respond(User $user, Invitation $invitation)
    {
        return $user->email == $invitation->recipient_email;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Invitation $invitation
     * @return mixed
     */
    public function forceDelete(User $user, Invitation $invitation)
    {
        //
    }
}
