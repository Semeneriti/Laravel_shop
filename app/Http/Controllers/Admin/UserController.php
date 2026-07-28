<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function index(): Factory|View
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create(): Factory|View
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $this->userService->createFromAdmin($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Пользователь создан!');
    }

    public function show(User $user): Factory|View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): Factory|View
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $this->userService->update($user, $request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Пользователь обновлён!');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->userService->delete($user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Пользователь удалён!');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->userService->resetPassword($user, $request->password);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Пароль сброшен!');
    }
}
