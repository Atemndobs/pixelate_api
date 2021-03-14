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
 * @property int $id
 * @property string|null $commenter_id
 * @property string|null $commenter_type
 * @property string|null $guest_name
 * @property string|null $guest_email
 * @property string $commentable_type
 * @property string $commentable_id
 * @property string $comment
 * @property bool $approved
 * @property int|null $child_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $love_reactant_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commenter
 * @property-read mixed $reacter_id
 * @property-read \Cog\Laravel\Love\Reactant\Models\Reactant|null $loveReactant
 * @property-read Comment|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Comment joinReactionCounterOfType(string $reactionTypeName, ?string $alias = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment joinReactionTotal(?string $alias = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereChildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommenterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereGuestEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereGuestName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLoveReactantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereNotReactedBy(\Cog\Contracts\Love\Reacterable\Models\Reacterable $reacterable, ?string $reactionTypeName = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereReactedBy(\Cog\Contracts\Love\Reacterable\Models\Reacterable $reacterable, ?string $reactionTypeName = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @mixin \Eloquent
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
