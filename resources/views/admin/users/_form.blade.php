<div class="mb-3">
    <label for="first_name" class="form-label">Имя</label>
    <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name ?? '') }}" required>
    @error('first_name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="last_name" class="form-label">Фамилия</label>
    <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name ?? '') }}" required>
    @error('last_name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required>
    @error('email')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="role_id" class="form-label">Роль</label>
    <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
        @foreach($roles as $role)
            <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id ?? '') == $role->id)>
                {{ $role->name }}
            </option>
        @endforeach
    </select>
    @error('role_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status" class="form-label">Статус</label>
    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
        <option value="active" @selected(old('status', $user->status ?? 'active') === 'active')>Активен</option>
        <option value="blocked" @selected(old('status', $user->status ?? 'active') === 'blocked')>Заблокирован</option>
    </select>
    @error('status')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
