<?php

namespace App\Providers;

use App\Repositories\Contracts\ChatRepositoryInterface;
use App\Repositories\Contracts\DesignRepositoryInterface;
use App\Repositories\Contracts\InvitationRepositoryInterface;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\ChatRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Eloquent\DesignRepository;
use App\Repositories\Eloquent\InvitationRepository;
use App\Repositories\Eloquent\MessageRepository;
use App\Repositories\Eloquent\TeamRepository;
use App\Repositories\Contracts\TeamRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(DesignRepositoryInterface::class, DesignRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(InvitationRepositoryInterface::class, InvitationRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(ChatRepositoryInterface::class, ChatRepository::class);
    }
}
