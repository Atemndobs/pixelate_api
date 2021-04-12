<?php

namespace App\Models;

use Cviebrock\EloquentTaggable\Taggable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableInterface;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Illuminate\Notifications\Notifiable;
use Laravelista\Comments\Commentable;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\stringContains;
use function RingCentral\Psr7\str;

/**
 * Class Post
 *
 * @package App\Models
 * @version December 2, 2020, 6:32 pm UTC
 * @OA\Schema (
 *      @OA\Xml(name="Post"),
 *      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="caption", type="string", example="the sea life"),
 *      @OA\Property(property="location", type="string", example="Dusseldorf"),
 *      @OA\Property(property="imageUrl", type="string", example="http://localhost:8000/storage/3B5wcGnEbnFaAYsjMxk5P1V1fAGKaVhviC3EO0Gd.png"),
 *      @OA\Property(property="created_dates", type="object",
 *          @OA\Property(property="created_at_human", description="Date Created formatted", example="52 minutes ago"),
 *          @OA\Property(property="created_at", description="Raw unfarmatted Date ", example="2020-11-09T20:04:11.000000Z"),
 *      ),
 *      @OA\Property(property="updated_dates", type="object",
 *          @OA\Property(property="updated_at_human", description="Date Updated formatted", example="52 minutes ago"),
 *          @OA\Property(property="updated_at", description="Raw unfarmatted update date ", example="2020-11-09T20:04:11.000000Z"),
 *      ),
 *      @OA\Property(property="user", ref="#/components/schemas/User"),
 *      @OA\Property(property="comment", ref="#/components/schemas/Comment"),
 * )
 */
class Post extends Model implements ReactableInterface
{
    use SoftDeletes, Reactable, HasFactory, Commentable, Taggable;


    public $table = 'posts';


    protected $dates = ['deleted_at'];

    protected $appends = ['reacter_id'];




    public $fillable = [
        'user_id',
        'caption',
        'imageUrl',
        'location',
        'likers',
        'latest_comment',
        'likers_hash'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'caption' => 'string',
        'location' => 'string',
        'likers' => 'array',
        'latest_comment'=> 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'caption' => 'required'
    ];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

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
