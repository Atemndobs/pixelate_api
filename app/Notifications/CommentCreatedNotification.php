<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentCreatedNotification extends Notification
{
    use Queueable;


    /**
     * @var Comment|Builder|Model|object|null
     */
    public $comment;

    /**
     * CommentCreatedNotification constructor.
     */
    public function __construct()
    {
        $this->comment = \App\Models\Comment::latest()->first();
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hey '. auth()->user()->username)
            ->line('You Just received a new comment.')
            ->from($this->comment->commenter->email)
            ->action('Notification Action', url('http://dejavu.atmkng.de/'))
            ->line('Check out the discussions on DejaVu')
             ;
    }

    public function toDatabase()
    {
        return [
           'commenter' => $this->comment->commenter->name,
            'comment' => $this->comment->comment
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
