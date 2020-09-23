<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as PasswordResetNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class ResetPassword extends PasswordResetNotification
{
    use Queueable;


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(config('app.client_url').'/password/reset/'.$this->token).'?email='.urlencode(($notifiable->email));
        return (new MailMessage)
                    ->line('This is an email response to your password reset request')
                    ->action('Reset Password', $url)
                    ->line('If you did now request a password reset please ignore this email');
    }
}
