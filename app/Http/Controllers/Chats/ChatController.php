<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\User;
use App\Repositories\Contracts\ChatRepositoryInterface;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Eloquent\MessageRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ChatController extends Controller
{


    /**
     * @var ChatRepositoryInterface
     */
    protected ChatRepositoryInterface $chatRepository;

    /**
     * @var MessageRepositoryInterface
     */
    protected MessageRepositoryInterface $messageRepository;

    /**
     * ChatController constructor.
     * @param ChatRepositoryInterface $chatRepository
     * @param MessageRepositoryInterface $messageRepository
     */
    public function __construct(ChatRepositoryInterface $chatRepository,
                                MessageRepositoryInterface $messageRepository)
    {
        $this->chatRepository = $chatRepository;
        $this->messageRepository = $messageRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function getUserChats()
    {
        $chat = $this->chatRepository->getUserChats();

        return ChatResource::collection($chat);
    }

    public function sendMessage(Request $request)
    {
        // validation
        $this->validate($request, [
           'recipient' => 'required' ,
           'body' => 'required' ,
        ]);

        $recipient = $request->recipient;
        $user = auth()->user();
        $body = $request->body;

        // Checking that users dont have moerthan one chat
        // $chat = User::find(auth()->id())->getChatWithUser($recipients);

         $chat = auth()->user()->getChatWithUser($recipient);
         if (!$chat){
             $chat = $this->chatRepository->create([]);
             $this->chatRepository->createParticipants($chat->id, [$user->id, $recipient]);
         }
         // add message to chat
        $message = $this->messageRepository->create([
           'user_id' => $user->id,
            'chat_id' => $chat->id,
            'body' => $body,
            'last_read' => null
        ]);

         return new MessageResource($message);
    }

    public function getChatMessages(int $id)
    {

    }

    public function markAsRead(int $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroyMessage(int $id)
    {
        //
    }
}
