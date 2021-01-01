<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Trade
 *
 * @property int $id
 * @property string $market
 * @property int|null $design_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Design[] $design
 * @property-read int|null $design_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Trade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trade query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereDesignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereMarket($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trade whereUserId($value)
 * @mixin \Eloquent
 */
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
