<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'design_id',
        'market',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function design()
    {
        return $this->hasMany(Design::class);
    }

}
