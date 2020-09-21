<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Design;
use App\Models\Invitation;
use App\Models\Team;
use App\Policies\CommentPolicy;
use App\Policies\DesignPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Design::Class =>  DesignPolicy::class,
        Comment::Class => CommentPolicy::class,
        Team::Class => TeamPolicy::class,
        Invitation::Class => InvitationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
