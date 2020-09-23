<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 */
class Team extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'slug'
    ];

    protected static function boot()
    {

        parent::boot();
        // when team is created add current user as team member

        static::created(function (Team $team){
            // auth()->user()->teams()->attach($team->id);
            $team->members()->attach(auth()->id());
        });

        static::deleting(function (Team $team){
            $team->members()->sync([]);
        });
    }

    public function owner()
    {

        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {

        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id')->withTimestamps();
    }

    public function designs()
    {

        return $this->hasMany(Design::class);
    }

    public function hasUser(User $user)
    {

        return $this->members()
            ->where('user_id', $user->id)
            ->first() ? true : false;
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, );
    }

    public function hasPendingInvite($email)
    {
        return (bool)$this->invitations()->where('recipient_email', $email)->count();
    }
}
