<?php

namespace App\Repositories;

use App\Models\Post;


/**
 * Class PostRepository
 * @package App\Repositories
 * @version December 2, 2020, 6:32 pm UTC
*/

class PostRepository extends \App\Repositories\Eloquent\BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'caption',
        'imageUrl',
        'location',
        'user_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Post::class;
    }

}
