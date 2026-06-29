<?php

namespace App\Services;

use App\DTO\RegisterDto;
use App\DTO\UpdateProfileDto;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    public function register(RegisterDto $dto): User
    {
        $user = new User();
        $user->first_name = $dto->firstName;
        $user->last_name = $dto->lastName;
        $user->email = $dto->email;
        $user->phone = $dto->phone;
        $user->password = Hash::make($dto->password);
        $user->save();

        return $user;
    }

    public function updateProfile(UpdateProfileDto $dto): void
    {
        $user = Auth::user();

        if (!$user) {
            throw new AuthenticationException('Пользователь не авторизован');
        }

        $user->fill($dto->toArray());
        $user->save();
    }

    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Текущий пароль неверен',
            ]);
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
