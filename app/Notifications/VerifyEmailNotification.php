<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );

        return (new MailMessage())
            ->subject('Подтверждение email - Laravel Shop')
            ->greeting('Здравствуйте, ' . $notifiable->first_name . '!')
            ->line('Благодарим вас за регистрацию в нашем магазине.')
            ->line('Для завершения регистрации подтвердите ваш email, нажав на кнопку ниже.')
            ->action('Подтвердить email', $url)
            ->line('Ссылка действительна в течение 60 минут.')
            ->line('Если вы не регистрировались, просто проигнорируйте это письмо.')
            ->salutation('С уважением, команда Laravel Shop');
    }
}
