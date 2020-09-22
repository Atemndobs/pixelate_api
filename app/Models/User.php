<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

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
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, SpatialTrait;

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
        'formatted_address'
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

    protected $appends = [
        'photo_url'
    ];

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


    /**
     *
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

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'recipient_email', 'email');
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'participants');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getChatWithUser($user_id)
    {
        $chat = $this->chats()->whereHas('participants', function ($query) use ($user_id){
            $query->where('user_id', $user_id);
        })->first();

        return $chat;
    }
}
