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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 */
	class Chat extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
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
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Design
 *
 * @method static findOrFail($id)
 * @property int $id
 * @property int $user_id
 * @property string $image
 * @property string|null $title
 * @property string|null $description
 * @property string|null $slug
 * @property int $is_live
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $upload_successful
 * @property string $disk
 * @property-read mixed $images
 * @property-read array $tag_array
 * @property-read array $tag_array_normalized
 * @property-read string $tag_list
 * @property-read string $tag_list_normalized
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cviebrock\EloquentTaggable\Models\Tag[] $tags
 * @property-read int|null $tags_count
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
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereUploadSuccessful($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withAllTags($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withAnyTags($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withoutAllTags($tags, $includeUntagged = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withoutAnyTags($tags, $includeUntagged = false)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property-read int|null $likes_count
 * @property int|null $team_id
 * @property-read \App\Models\Team|null $team
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereTeamId($value)
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
 */
	class Invitation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Like
 *
 * @property int $id
 * @property int $user_id
 * @property string $likeable_type
 * @property int $likeable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $likeable
 * @method static \Illuminate\Database\Eloquent\Builder|Like newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like query()
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereLikeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereLikeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereUserId($value)
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
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Team
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @mixin \Eloquent
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Design[] $designs
 * @property-read int|null $designs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 * @property-read int|null $members_count
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invitation[] $invitations
 * @property-read int|null $invitations_count
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $tagline
 * @property mixed|null $location
 * @property string|null $formatted_address
 * @property int $available_to_hire
 * @property string|null $about
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Design[] $designs
 * @property-read int|null $designs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User comparison($geometryColumn, $geometry, $relationship)
 * @method static \Illuminate\Database\Eloquent\Builder|User contains($geometryColumn, $geometry)
 * @method static \Illuminate\Database\Eloquent\Builder|User crosses($geometryColumn, $geometry)
 * @method static \Illuminate\Database\Eloquent\Builder|User disjoint($geometryColumn, $geometry)
 * @method static \Illuminate\Database\Eloquent\Builder|User distance($geometryColumn, $geometry, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|User distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|User distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|User distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|User distanceSphereValue($geometryColumn, $geometry)
 * @method static \Illuminate\Database\Eloquent\Builder|User distanceValue($geometryColumn, $geometry)
 * @method static \Illuminate\Database\Eloquent\Builder|User doesTouch($geometryColumn, $geometry)
 * @method static \Illuminate\Database\Eloquent\Builder|User equals($geometryColumn, $geometry)
 * @method static \Illuminate\Database\Eloquent\Builder|User intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User newModelQuery()
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|User orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|User orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|User overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvailableToHire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFormattedAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTagline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User within($geometryColumn, $polygon)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Chat[] $chats
 * @property-read int|null $chats_count
 * @property-read mixed $photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invitation[] $invitations
 * @property-read int|null $invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $ownedTeams
 * @property-read int|null $owned_teams_count
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

