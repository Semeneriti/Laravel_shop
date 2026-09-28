<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;

class UserNotificationService
{
    public function sendEmailVerification(User $user): void
    {
        if (!$user->hasVerifiedEmail()) {
            $user->notify(new VerifyEmailNotification());
        }
    }
}
