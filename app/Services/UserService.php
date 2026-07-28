<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function createFromAdmin(array $data): User
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'status' => $data['status'],
            'role_id' => $data['role_id'],
            'password' => Hash::make(Str::random(10)),
        ]);
    }

    public function update(User $user, array $data): User
    {
        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'status' => $data['status'],
            'role_id' => $data['role_id'],
        ]);

        return $user;
    }

    public function resetPassword(User $user, string $password): void
    {
        $user->update([
            'password' => Hash::make($password),
        ]);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
