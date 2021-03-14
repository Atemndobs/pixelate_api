<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PostRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Post;
use App\Validators\PostValidator;

/**
 * Class PostRepository.
 *
 * @package namespace App\Repositories;
 */
class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Post::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function applyTags($id, array $data)
    {
        $post = $this->find($id);
        return $post->retag($data);
    }
    public function update(array $attributes, $id)
    {
        parent::update($attributes, $id);
        $this->applyTags($id, $attributes['tags']??[]);
    }

    public function addLikers($post)
    {
        $liker = auth()->id();
        $likers = $post->likers;
        if (!empty($likers) && !(\Arr::has($likers, $liker))) {
            $this->update([
                'likers' => $likers[$liker]
            ], $post->id);
        }

        if (empty($likers)) {
            $this->update([
                'likers' => $likers[$liker]
            ], $post->id);
        }
    }
}
