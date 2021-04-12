<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PostRepository.
 *
 * @package namespace App\Repositories;
 */
interface PostRepositoryInterface extends RepositoryInterface
{
    //
    public function applyTags($id, array $data);
}
