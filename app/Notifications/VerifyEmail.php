<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

// use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification
{
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $appUrl = config('app.url');

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinute(60),
            ['user' => $notifiable->id]
        );

         #return str_replace(url('/api'), $appUrl, $url);
         return str_replace(url('/api'), 'http://atemkeng.com/api', $url);
    }

}
