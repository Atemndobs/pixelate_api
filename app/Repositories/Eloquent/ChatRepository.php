<?php


namespace App\Repositories\Eloquent;


use App\Models\Chat;
use App\Repositories\Contracts\ChatRepositoryInterface;

class ChatRepository extends BaseRepository implements ChatRepositoryInterface
{
    public function model()
    {
        return Chat::class;
    }

    public function createParticipants($chat_id, array $data)
    {
        $chat = $this->model->find($chat_id);
        $chat->participants()->sync($data);
    }

    public function getUserChats()
    {
      //  dd(auth()->user()->chats()->with(['messages', 'participants'])->get());
        return auth()->user()->chats()
            ->with(['messages', 'participants'])
            ->get();
    }
}
