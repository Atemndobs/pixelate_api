<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property int $user_id
 * @property string $body
 * @property string $commentable_type
 * @property int $commentable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $commentable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $commenter_id
 * @property string|null $commenter_type
 * @property string|null $guest_name
 * @property string|null $guest_email
 * @property string $comment
 * @property int $approved
 * @property int|null $child_id
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereChildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommenterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereGuestEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereGuestName($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravelista\Comments\Comment[] $children
 * @property-read int|null $children_count
 * @property-read Model|\Eloquent $commenter
 * @property-read mixed $reacter_id
 * @property-read \Cog\Laravel\Love\Reactant\Models\Reactant $loveReactant
 * @property-read \Laravelista\Comments\Comment|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Comment joinReactionCounterOfType($reactionTypeName, $alias = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment joinReactionTotal($alias = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereNotReactedBy(\Cog\Contracts\Love\Reacterable\Models\Reacterable $reacterable, $reactionTypeName = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereReactedBy(\Cog\Contracts\Love\Reacterable\Models\Reacterable $reacterable, $reactionTypeName = null)
 */
class Comment extends \Laravelista\Comments\Comment
{
    use HasFactory;

    protected $appends = ['reacter_id'];



    public function getReacterIdAttribute()
    {
        return  \Auth::id();
    }

}
