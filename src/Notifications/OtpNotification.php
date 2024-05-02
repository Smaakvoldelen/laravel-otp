<?php

namespace Smaakvoldelen\Otp\Notifications;

use Closure;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class OtpNotification extends Notification
{
    /**
     * The callback that should be used to build the mail message.
     */
    public static ?Closure $toMailCallback = null;

    /**
     * @return void
     */
    public static function toMailUsing(?Closure $callback)
    {
        static::$toMailCallback = $callback;
    }

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $token)
    {
        //
    }

    /**
     * Get the notification's channels.
     *
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return $this->buildMailMessage();
    }

    /**
     * Get the one-time password notification mail message.
     */
    protected function buildMailMessage(): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('One-time password notification'))
            ->line(Lang::get('You are receiving this email because we received a one-time password request for your account.'))
            ->line(Lang::get('The one-time password is: :otp', ['otp' => $this->token]))
            ->line(Lang::get('This one-time password will expire in :count minutes.', ['count' => config('otp.expire', 10)]))
            ->line(Lang::get('If you did not request a one-time password, no further action is required.'));
    }
}
