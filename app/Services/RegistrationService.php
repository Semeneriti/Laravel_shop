<?php

namespace App\Services;

use App\DTO\RegisterDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

        // TODO: после изучения очередей добавить событие для отправки приветственного письма:
        // event(new Registered($user));

        return $user;
    }
}
