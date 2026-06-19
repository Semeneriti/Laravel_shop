<?php

namespace App\Http\Controllers;

use App\DTO\RegisterDto;
use App\Http\Requests\RegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegistrationService $service
    ) {}


    public function showRegistrationForm(): Factory|View
    {
        return view('auth.register');
    }


    public function register(RegisterRequest $request): RedirectResponse
    {
        $dto = RegisterDto::fromRequest($request);
        $user = $this->service->register($dto);

        return redirect()
            ->route('login.form')
            ->with('status', 'Регистрация прошла успешно');
    }
}
