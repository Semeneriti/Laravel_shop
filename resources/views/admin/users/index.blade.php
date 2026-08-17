@extends('layouts.app')

@section('title', 'Управление пользователями')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Пользователи</h1>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Создать пользователя</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->first()->name ?? '—' }}</td>
                        <td><span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $user->status_label }}</span></td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">Просмотр</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Редактировать</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пользователя?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
