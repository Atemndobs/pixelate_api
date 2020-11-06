<?php

namespace App\Models;

use App\Models\Traits\Likeable;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

/**
 * App\Models\Design
 *
 * @method static findOrFail($id)
 * @property int $id
 * @property int $user_id
 * @property string $image
 * @property string|null $title
 * @property string|null $description
 * @property string|null $slug
 * @property int $is_live
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $upload_successful
 * @property string $disk
 * @property-read mixed $images
 * @property-read array $tag_array
 * @property-read array $tag_array_normalized
 * @property-read string $tag_list
 * @property-read string $tag_list_normalized
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cviebrock\EloquentTaggable\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Design isNotTagged()
 * @method static \Illuminate\Database\Eloquent\Builder|Design isTagged()
 * @method static \Illuminate\Database\Eloquent\Builder|Design newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Design newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Design query()
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereIsLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereUploadSuccessful($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withAllTags($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withAnyTags($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withoutAllTags($tags, $includeUntagged = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Design withoutAnyTags($tags, $includeUntagged = false)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property-read int|null $likes_count
 */
class Design extends Model
{
    use Taggable, Likeable, HasFactory;


    protected $fillable=[
        'user_id',
        'team_id',
        'image',
        'title',
        'description',
        'slug',
        'close_to_comment',
        'is_live',
        'upload_successful',
        'disk',
        'trade_id'
    ];

    public function user()
    {
      return  $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function trade()
    {
        return $this->belongsTo(Trade::class);
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

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->orderBy('created_at', 'asc');
    }


}
