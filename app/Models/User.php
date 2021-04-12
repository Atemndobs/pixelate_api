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

    protected $guard = 'admin';

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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
