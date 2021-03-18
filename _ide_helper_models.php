<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Chat
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $latest_message
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $participants
 * @property-read int|null $participants_count
 * @method static \Illuminate\Database\Eloquent\Builder|Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Chat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Chat extends \Eloquent {}
}

namespace App\Models{
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
 * @property array $likers
 * @property string $likers_hash
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLikers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLikersHash($value)
 */
	class Comment extends \Eloquent implements \Cog\Contracts\Love\Reactable\Models\Reactable {}
}

namespace App\Models{
/**
 * App\Models\Design
 *
 * @OA\Schema (
 *      @OA\Xml(name="Design"),
 *      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="title", type="string", example="the sea life"),
 *      @OA\Property(property="slug", type="string", example="the-sea-life"),
 *      @OA\Property(property="disk", type="string", example="public"),
 *      @OA\Property(property="is_live", type="boolean", example=0),
 *      @OA\Property(property="images", type="object",
 *          @OA\Property(property="thumbnail",type="string", example="http://localhost:8000/storage/uploads/designs/thumbnail/"),
 *          @OA\Property(property="large", type="string", example="http://localhost:8000/storage/uploads/designs/large/"),
 *          @OA\Property(property="original", type="string", example="http://localhost:8000/storage/uploads/designs/original/"),
 *      ),
 *      @OA\Property(property="likes_count", type="integer", example=0),
 *      @OA\Property(property="uploaded_successful", type="interger", example=null),
 *      @OA\Property(property="tag_list", type="object",
 *          @OA\Property(property="tag", description="List of Tags added to design, array of many tags", example={"Cool", "Bright Day"}),
 *          @OA\Property(property="normalized", description="List of Tags normalized, lowercase and slugged", example={"cool", "bright-day"}),
 *      ),
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
 * @property string $image
 * @property string|null $title
 * @property string|null $description
 * @property string|null $slug
 * @property int $is_live
 * @property int|null $trade_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $upload_successful
 * @property string $disk
 * @property int|null $team_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read mixed $images
 * @property-read array $tag_array
 * @property-read array $tag_array_normalized
 * @property-read string $tag_list
 * @property-read string $tag_list_normalized
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cviebrock\EloquentTaggable\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\Team|null $team
 * @property-read \App\Models\Trade|null $trade
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Design isNotTagged()
 * @method static \Illuminate\Database\Eloquent\Builder|Design isTagged()
 * @method static \Illuminate\Database\Eloquent\Builder|Design newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Design newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Design query()
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereIsLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereTradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereUploadSuccessful($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withAllTags($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withAnyTags($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withoutAllTags($tags, bool $includeUntagged = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withoutAnyTags($tags, bool $includeUntagged = false)
 * @mixin \Eloquent
 */
	class Design extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Invitation
 *
 * @property int $id
 * @property string $recipient_email
 * @property int $sender_id
 * @property int $team_id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $recipient
 * @property-read \App\Models\User|null $sender
 * @property-read \App\Models\Team $team
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereRecipientEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Invitation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Like
 *
 * @property-read Model|\Eloquent $likeable
 * @method static \Illuminate\Database\Eloquent\Builder|Like newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like query()
 * @mixin \Eloquent
 */
	class Like extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Message
 *
 * @property int $id
 * @property string $body
 * @property string|null $last_read
 * @property int $user_id
 * @property int $chat_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chat $chat
 * @property-read \App\Models\User $sender
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Query\Builder|Message onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereLastRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Message withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Message withoutTrashed()
 * @mixin \Eloquent
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
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
	class Post extends \Eloquent implements \Cog\Contracts\Love\Reactable\Models\Reactable {}
}

namespace App\Models{
/**
 * App\Models\Reaction
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reaction query()
 * @mixin \Eloquent
 */
	class Reaction extends \Eloquent implements \Cog\Contracts\Love\Reaction\Models\Reaction {}
}

namespace App\Models{
/**
 * App\Models\Team
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Design[] $designs
 * @property-read int|null $designs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 * @property-read int|null $members_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Trade
 *
 * @property int $id
 * @property string $market
 * @property int|null $design_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Design[] $design
 * @property-read int|null $design_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Trade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trade query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereDesignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereMarket($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereUserId($value)
 * @mixin \Eloquent
 */
	class Trade extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @OA\Schema (
 *      required={"password"},
 *      @OA\Xml(name="User"),
 *      @OA\Property(property="id", type="integer", readOnly="true", example=1),
 *      @OA\Property(property="name", type="string", maxLength=32, example="Mittie Morissette"),
 *      @OA\Property(property="username", type="string", maxLength=32, example="pierce"),
 *      @OA\Property(property="email", type="string", readOnly="true", format="email", description="User unique email address", example="fanny256@email.com"),
 *      @OA\Property(property="email_verified_at", type="string", readOnly="true", format="date-time", description="Datetime marker of verification status", example="2019-02-25 12:59:20"),
 *      @OA\Property(property="two_factor_secret", type="number", example=null ),
 *      @OA\Property(property="two_factor_recovery_codes", type="number", example=null ),
 *      @OA\Property(property="tagline:", type="string", maxLength=32, example="Producer:"),
 *      @OA\Property(property="location", type="object",
 *          @OA\Property(property="type",type="string",example="point"),
 *          @OA\Property(property="coordinates",
 *              example={8.503972,51.017243}
 *          ),
 *      ),
 *      @OA\Property(property="formatted_address", example="811 Sibyl Bypass Suite 783\n New Rita, AL 48220-0930" ),
 *      @OA\Property(property="available_to_hire", type="boolean", example=1 ),
 *      @OA\Property(property="about", type="string", example="VERY deeply with a soldier on each." ),
 * 
 *      @OA\Property(property="trade_id", type="number", example=null ),
 *      @OA\Property(property="current_team_id", type="number", example=null ),
 *      @OA\Property(property="profile_photo_path", type="number", example=null ),
 *      @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 *      @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 *      @OA\Property(property="photo_url:", type="string", maxLength=32, example="https://www.gravatar.com/avatar/97bd1823e00f02eb71a0b709425152a7jpg?s=200&d=mm:"),
 * )
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $tagline
 * @property mixed|null $location
 * @property string|null $formatted_address
 * @property int $available_to_hire
 * @property string|null $about
 * @property int|null $trade_id
 * @property int|null $current_team_id
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $love_reacter_id
 * @property string $uuid
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $approvedComments
 * @property-read int|null $approved_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Chat[] $chats
 * @property-read int|null $chats_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Design[] $designs
 * @property-read int|null $designs_count
 * @property-read string $photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \Cog\Laravel\Love\Reacter\Models\Reacter|null $loveReacter
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $ownedTeams
 * @property-read int|null $owned_teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Trade[] $trade
 * @property-read int|null $trade_count
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User distance($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User newModelQuery()
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User newQuery()
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User query()
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereAbout($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereAvailableToHire($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereAvatar($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereEmail($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereFormattedAddress($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereId($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereLocation($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereLoveReacterId($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereName($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User wherePassword($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereTagline($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereTradeId($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereUsername($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereUuid($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User within($geometryColumn, $polygon)
 * @mixin \Eloquent
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User whereLastLoginIp($value)
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject, \Illuminate\Contracts\Auth\MustVerifyEmail, \Cog\Contracts\Love\Reacterable\Models\Reacterable, \Rennokki\Befriended\Contracts\Following, \Rennokki\Befriended\Contracts\Blocking, \Rennokki\Befriended\Contracts\Follower, \Rennokki\Befriended\Contracts\Followable, \Rennokki\Befriended\Contracts\Blocker, \Rennokki\Befriended\Contracts\Blockable {}
}

