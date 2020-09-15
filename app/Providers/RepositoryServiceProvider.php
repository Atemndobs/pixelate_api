<?php

namespace App\Providers;

use App\Repositories\Contracts\DesignRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\DesignRepository;
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
    }
}
