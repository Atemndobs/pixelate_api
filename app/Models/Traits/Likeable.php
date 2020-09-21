<?php


namespace App\Models\Traits;


use App\Models\Like;

trait Likeable
{
    // automatically watch the appended  function (Likeable) and carryout
    // the included action(delete) on the variable (model)
    public static function bootLikeable()
    {
        static::deleting(function ($model){
            $model->removeLikes();
        });
    }

    // delete likes when model is deleted (see bootLikeable)

    public function removeLikes()
    {
        if ($this->likes()->count()){
            $this->likes()->delete();
        }
    }
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like()
    {
        if (! auth()->check()) {
            return;
        }

        // check id current user Liked already
        if ($this->isLikedByUser(auth()->id())){
            return;
        }

        $this->likes()->create(['user_id' => auth()->id()]);
      //  return response()->json(['Success' => 'Like ']);
    }


    public function unlike()
    {
        if (!auth()->check()) return;
        if (!$this->isLikedByUser(auth()->id())) return;

        $this->likes()->where('user_id', auth()->id())->delete();
      //  return response()->json(['message' => 'Unliked']);
    }


    public function isLikedByUser(int $user_id)
    {
        return (bool)$this->likes()->where('user_id' , $user_id)->count();
    }

}
