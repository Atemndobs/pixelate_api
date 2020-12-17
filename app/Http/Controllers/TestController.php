<?php

namespace App\Http\Controllers;

use App\Events\Hallo;
use Illuminate\Support\Facades\Response;

class TestController extends Controller
{

    public function index()
    {
        $data = [
            'message' =>'hello world',
            'sender' =>  'Atem',
            'channel' => 'comment-channel'
        ];

        /*

        $options = array(
            'cluster' => 'eu',
            'useTLS' => true
        );
       $pusher = new Pusher(
            '8643c99a8b00ff38c513',
            'b94632bb3eafdf94bfa1',
            '987918',
            $options
        );

        $data = [
            'message' =>'hello world',
            'sender' =>  'Atem'
        ];

        try {
            $pusher->trigger('comment-channel', 'my-event', $data);
        }catch (\Exception $exception) {
            return response($exception->getMessage()
                .$pusher->get_channel_info('comment-channel')
                ,  404);
        }*/

        event(new Hallo());

        return Response::json( $data, 200);

    }
}
