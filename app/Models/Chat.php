<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Chat
 *
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
