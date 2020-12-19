<?php


namespace App\Services;


class FollowService
{
    private $user;

    /**
     * FollowService constructor.
     * @param $user
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }
}
