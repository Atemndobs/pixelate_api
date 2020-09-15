<?php

namespace App\Models;

use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @method static findOrFail($id)
 */
class Design extends Model
{
    use Taggable;

    protected $fillable=[
        'user_id',
        'image',
        'title',
        'description',
        'slug',
        'close_to_comment',
        'is_live',
        'upload_successful',
        'disk'
    ];

    public function user()
    {
      return  $this->belongsTo(User::class);
    }

    public function getImagesAttribute()
    {
        return [
            'thumbnail' => $this->getPath('thumbnail'),
            'large' => $this->getPath('large'),
            'original' => $this->getPath('original'),
        ];
    }

    protected function getPath(string $size)
    {
        return Storage::disk($this->disk)
            ->url("uploads/designs/{$size}/".$this->image);
    }
}
