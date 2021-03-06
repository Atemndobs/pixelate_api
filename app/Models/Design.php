<?php

namespace App\Models;

use App\Models\Traits\Likeable;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Design
 *
 * @OA\Schema (
 *      @OA\Xml(name="Design"),
 *      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="title", type="string", example="the sea life"),
 *      @OA\Property(property="slug", type="string", example="the-sea-life"),
 *      @OA\Property(property="disk", type="string", example="public"),
 *      @OA\Property(property="is_live", type="boolean", example=0),
 *      @OA\Property(property="images", type="object",
 *          @OA\Property(property="thumbnail",type="string", example="http://localhost:8000/storage/uploads/designs/thumbnail/"),
 *          @OA\Property(property="large", type="string", example="http://localhost:8000/storage/uploads/designs/large/"),
 *          @OA\Property(property="original", type="string", example="http://localhost:8000/storage/uploads/designs/original/"),
 *      ),
 *      @OA\Property(property="likes_count", type="integer", example=0),
 *      @OA\Property(property="uploaded_successful", type="interger", example=null),
 *      @OA\Property(property="tag_list", type="object",
 *          @OA\Property(property="tag", description="List of Tags added to design, array of many tags", example={"Cool", "Bright Day"}),
 *          @OA\Property(property="normalized", description="List of Tags normalized, lowercase and slugged", example={"cool", "bright-day"}),
 *      ),
 *      @OA\Property(property="created_dates", type="object",
 *          @OA\Property(property="created_at_human", description="Date Created formatted", example="52 minutes ago"),
 *          @OA\Property(property="created_at", description="Raw unfarmatted Date ", example="2020-11-09T20:04:11.000000Z"),
 *      ),
 *      @OA\Property(property="updated_dates", type="object",
 *          @OA\Property(property="updated_at_human", description="Date Updated formatted", example="52 minutes ago"),
 *          @OA\Property(property="updated_at", description="Raw unfarmatted update date ", example="2020-11-09T20:04:11.000000Z"),
 *      ),
 *      @OA\Property(property="user", ref="#/components/schemas/User"),
 *      @OA\Property(property="comment", ref="#/components/schemas/Comment"),
 * )
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
