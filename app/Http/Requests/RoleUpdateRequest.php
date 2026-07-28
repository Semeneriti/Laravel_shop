<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role');

        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($roleId)],
            'slug' => ['required', 'string', 'max:50', Rule::unique('roles', 'slug')->ignore($roleId)],
        ];
    }
}
