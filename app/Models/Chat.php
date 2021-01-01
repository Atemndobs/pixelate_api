<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
class Chat extends Model
{
    use HasFactory;
    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function getLatestMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }

    public function isUnreadForUser($user_id)
    {
        return (bool)$this->messages()
            ->whereNull('last_read')
            ->where('user_id', '<>', $user_id)
            ->count();
    }
    public function unreadMessages($user_id)
    {
        return $this->messages()
            ->whereNull('last_read')
            ->where('user_id', '<>', $user_id)
            ->count();
    }

    public function markAsReadForUser($user_id)
    {
        return (bool)$this->messages()
                ->whereNull('last_read')
                ->where('user_id', '<>', $user_id)
                ->update([
                    'last_read' => Carbon::now()
                ]);
    }

}
