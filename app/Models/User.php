<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravelista\Comments\Commentable;
use Rennokki\Befriended\Contracts\Blocking;
use Rennokki\Befriended\Contracts\Following;
use Rennokki\Befriended\Traits\Block;
use Rennokki\Befriended\Traits\Follow;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Cog\Contracts\Love\Reacterable\Models\Reacterable as ReacterableInterface;
use Cog\Laravel\Love\Reacterable\Models\Traits\Reacterable;

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
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail, ReacterableInterface, Following, Blocking
{
    use Notifiable, SpatialTrait, HasFactory, Reacterable, Commentable, Follow, Block;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tagline',
        'about',
        'username',
        'location',
        'available_to_hire',
        'formatted_address',
        'trade_id',
        'last_login_at',
        'last_login_ip'
    ];

    /**
     * @var array|string[]
     */
    protected array $spatialFields = [
        'location'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'photo_url',
       // 'follow'
    ];

    /**
     * @return HasMany
     */
    public function trade()
    {
        return $this->hasMany(Trade::class);
    }

    /**
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'jpg?s=200&d=mm';
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }



    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @param $token
     */
    public function setPasswordNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * @return HasMany
     */
    public function designs()
    {
        return $this->hasMany(Design::class);
    }
    /**
     * @return HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // teams that user belongs to

    /**
     * @return BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * @return BelongsToMany
     */
    public function ownedTeams()
    {
        return $this->teams()->where('owner_id', $this->id);
    }

    /**
     * @param Team $team
     * @return int
     */
    public function isOwnerOfTeam(Team $team)
    {
        return $this->teams()
            ->where('id', $team->id)
            ->where('owner_id', $this->id)
            ->count();
    }

    /**
     * @return HasMany
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'recipient_email', 'email');
    }

    /**
     * @return BelongsToMany
     */
    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'participants');
    }

    /**
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * @param $user_id
     * @return Builder|Model|BelongsToMany|mixed|object|null
     */
    public function getChatWithUser($user_id)
    {
        $chat = $this->chats()->whereHas('participants', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->first();

        return $chat;
    }
}
