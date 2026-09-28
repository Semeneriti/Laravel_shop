<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jobs;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeAfterVerificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int $userId,
    ) {
        $this->onQueue('users.notifications.welcome');
    }

    public function handle(): void
    {
        $user = User::query()->find($this->userId);

        if (!$user) {
            return;
        }

        Mail::to($user)->send(new WelcomeMail($user));
    }
}
