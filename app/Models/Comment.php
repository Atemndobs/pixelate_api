<?php

namespace App\Models;

use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Comment
 *
 * @OA\Schema (
 *      @OA\Xml(name="Comment"),
 *      @OA\Property(property="id", type="integer", readOnly="true", example=1),
 *      @OA\Property(property="comment", type="string", readOnly="true", example="this is a great app"),
 *      @OA\Property(property="commenter_id", type="integer", readOnly="true", example=1),
 *      @OA\Property(property="commentable_id", type="integer", readOnly="true", example=2),
 *
 *      @OA\Property(property="created_dates", type="object",
 *          @OA\Property(property="created_at_human", description="Date Created formatted", example="52 minutes ago"),
 *          @OA\Property(property="created_at", description="Raw unfarmatted Date ", example="2020-11-09T20:04:11.000000Z"),
 *      ),
 *      @OA\Property(property="updated_dates", type="object",
 *          @OA\Property(property="updated_at_human", description="Date Updated formatted", example="52 minutes ago"),
 *          @OA\Property(property="updated_at", description="Raw unfarmatted update date ", example="2020-11-09T20:04:11.000000Z"),
 *      ),
 * )
 */
class Comment extends \Laravelista\Comments\Comment implements ReactableInterface
{
    use HasFactory, Reactable;

    protected $appends = ['reacter_id'];

    protected $casts = [
        'likers' => 'array'
    ];

    public $fillable = [
        'likers',
        'likers_hash'
    ];


    public function getReacterIdAttribute()
    {
        return  \Auth::id();
    }

    public function addLiker()
    {
        $liker = auth()->id();

        $likers = $this->likers;

        if (!empty($likers) && !(in_array($liker, $likers))) {
            $likers[] = $liker;
            $this->likers =  $likers;
            $this->save();
        }
        if (empty($likers)) {
            $this->update([
                'likers' => [$liker]
            ]);
        }
    }

    public function removeLiker()
    {
        $liker = auth()->id();
        $likers = $this->likers;

        if (empty($likers)) {
            return;
        }

        if (in_array($liker, $likers)) {
            $key = array_search($liker, $likers);
            unset($likers[$key]);

            $this->likers =  $likers;
            $this->save();
        }
    }
}
