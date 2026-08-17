@extends('layouts.app')

@section('title', 'Редактировать пользователя')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-3">Редактировать пользователя</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            @include('admin.users._form')

            <button type="submit" class="btn btn-primary">Обновить</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Отмена</a>
        </form>

        <hr class="my-4">

        <form method="POST" action="{{ route('admin.users.password', $user) }}">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-md-4">
                    <input type="password" name="password" class="form-control" placeholder="Новый пароль" required>
                </div>
                <div class="col-md-4">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Подтвердите пароль" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-warning">Сбросить пароль</button>
                </div>
            </div>
        </form>
    </div>
@endsection
