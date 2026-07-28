<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\RoleDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService,
    ) {}

    public function index(): Factory|View
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create(): Factory|View
    {
        return view('admin.roles.create');
    }

    public function store(RoleStoreRequest $request): RedirectResponse
    {
        $dto = new RoleDto(
            name: $request->validated()['name'],
            slug: $request->validated()['slug'],
        );

        $this->roleService->create($dto);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Роль создана!');
    }

    public function edit(Role $role): Factory|View
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {
        $dto = new RoleDto(
            name: $request->validated()['name'],
            slug: $request->validated()['slug'],
        );

        $this->roleService->update($role, $dto);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Роль обновлена!');
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            $this->roleService->delete($role);
            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Роль удалена!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', $e->getMessage());
        }
    }
}
