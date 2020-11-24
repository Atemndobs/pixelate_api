<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * App\Models\User
 *
 * @OA\Schema(
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

 *      @OA\Property(property="trade_id", type="number", example=null ),
 *      @OA\Property(property="current_team_id", type="number", example=null ),
 *      @OA\Property(property="profile_photo_path", type="number", example=null ),
 *      @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 *      @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 *      @OA\Property(property="photo_url:", type="string", maxLength=32, example="https://www.gravatar.com/avatar/97bd1823e00f02eb71a0b709425152a7jpg?s=200&d=mm:"),
 * )
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $tagline
 * @property mixed|null $location
 * @property string|null $formatted_address
 * @property int $available_to_hire
 * @property string|null $about
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\App\Models\Design[] $designs
 * @property-read int|null $designs_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static Builder|User comparison($geometryColumn, $geometry, $relationship)
 * @method static Builder|User contains($geometryColumn, $geometry)
 * @method static Builder|User crosses($geometryColumn, $geometry)
 * @method static Builder|User disjoint($geometryColumn, $geometry)
 * @method static Builder|User distance($geometryColumn, $geometry, $distance)
 * @method static Builder|User distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static Builder|User distanceSphere($geometryColumn, $geometry, $distance)
 * @method static Builder|User distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static Builder|User distanceSphereValue($geometryColumn, $geometry)
 * @method static Builder|User distanceValue($geometryColumn, $geometry)
 * @method static Builder|User doesTouch($geometryColumn, $geometry)
 * @method static Builder|User equals($geometryColumn, $geometry)
 * @method static Builder|User intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User newModelQuery()
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User newQuery()
 * @method static Builder|User orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static Builder|User orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static Builder|User orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static Builder|User overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|User query()
 * @method static Builder|User whereAbout($value)
 * @method static Builder|User whereAvailableToHire($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereFormattedAddress($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLocation($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereTagline($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User within($geometryColumn, $polygon)
 * @mixin \Eloquent
 * @property-read Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, SpatialTrait, HasFactory;

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
        'trade_id'
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
        'photo_url'
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

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */




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
        return $this->teams()->where('owner_id' , $this->id);
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
     * @return Builder|\Illuminate\Database\Eloquent\Model|BelongsToMany|mixed|object|null
     */
    public function getChatWithUser($user_id)
    {
        $chat = $this->chats()->whereHas('participants', function ($query) use ($user_id){
            $query->where('user_id', $user_id);
        })->first();

        return $chat;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }
}
