@extends('layouts.app')

@section('title', 'Создать пользователя')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-3">Создать пользователя</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            @include('admin.users._form')

            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
