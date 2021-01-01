<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
class Invitation extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'recipient_email',
        'sender_id',
        'team_id',
        'token',
        'created_at',
        'updated_at'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function recipient()
    {
        return $this->hasOne(User::class, 'email', 'recipient_email');
    }

    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }
}
