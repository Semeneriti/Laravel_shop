<?php

namespace App\Http\Controllers\Auth;

use App\DTO\RegisterDto;
use App\DTO\UpdateProfileDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\RegistrationService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegistrationService $userService,
    ) {}

    public function showRegistrationForm(): Factory|View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $dto = RegisterDto::fromRequest($request);
        $user = $this->userService->register($dto);

        return redirect()
            ->route('login.form')
            ->with('status', 'Регистрация прошла успешно!');
    }

    public function showLoginForm(): Factory|View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ]);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login.form');
    }


    public function showProfile(): Factory|View
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }


    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $dto = UpdateProfileDto::fromRequest($request);
        $this->userService->updateProfile($dto);

        return redirect()
            ->route('profile.form')
            ->with('success', 'Профиль обновлён успешно!');
    }


    public function showChangePasswordForm(): Factory|View
    {
        return view('auth.change-password');
    }


    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $user = Auth::user();

        $this->userService->updatePassword(
            $user,
            $validatedData['current_password'],
            $validatedData['password']
        );

        return redirect()
            ->route('profile.form')
            ->with('success', 'Пароль успешно изменён!');
    }
}
