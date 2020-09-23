<?php


namespace App\Repositories\Eloquent;


use App\Models\Message;
use App\Repositories\Contracts\MessageRepositoryInterface;

class MessageRepository extends BaseRepository implements MessageRepositoryInterface
{
    public function model()
    {
        return Message::class;
    }

}
