<?php

namespace App\Repositories\Contracts;


use Illuminate\Http\Request;

interface DesignRepositoryInterface
{
    public function applyTags($id, array $data);

    public function like($id);

    public function isLikedByUser($id);

    public function addComment($design_id, array $data);

    public function find($id);
    public function withCriteria(...$criteria);

    public function search(Request $request);
}
