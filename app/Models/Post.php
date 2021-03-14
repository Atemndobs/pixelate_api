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
 * @property int $id
 * @property int $user_id
 * @property string $caption
 * @property string|null $imageUrl
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $love_reactant_id
 * @property array $likers
 * @property string $likers_hash
 * @property array|null $latest_comment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $approvedComments
 * @property-read int|null $approved_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read mixed $reacter_id
 * @property-read array $tag_array
 * @property-read array $tag_array_normalized
 * @property-read string $tag_list
 * @property-read string $tag_list_normalized
 * @property-read \Cog\Laravel\Love\Reactant\Models\Reactant|null $loveReactant
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cviebrock\EloquentTaggable\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Post isNotTagged()
 * @method static \Illuminate\Database\Eloquent\Builder|Post isTagged()
 * @method static \Illuminate\Database\Eloquent\Builder|Post joinReactionCounterOfType(string $reactionTypeName, ?string $alias = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post joinReactionTotal(?string $alias = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Query\Builder|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLatestComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLikers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLikersHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLoveReactantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereNotReactedBy(\Cog\Contracts\Love\Reacterable\Models\Reacterable $reacterable, ?string $reactionTypeName = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereReactedBy(\Cog\Contracts\Love\Reacterable\Models\Reacterable $reacterable, ?string $reactionTypeName = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withAllTags($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withAnyTags($tags)
 * @method static \Illuminate\Database\Query\Builder|Post withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Post withoutAllTags($tags, bool $includeUntagged = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withoutAnyTags($tags, bool $includeUntagged = false)
 * @method static \Illuminate\Database\Query\Builder|Post withoutTrashed()
 * @mixin Model
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
