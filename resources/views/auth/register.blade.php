@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Регистрация</h2>

                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        @if(->any())
                            <div class="alert alert-danger">
                                @foreach (->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="first_name" class="form-label">Имя</label>
                                <input type="text"
                                       name="first_name"
                                       id="first_name"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}"
                                       required>
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="last_name" class="form-label">Фамилия</label>
                                <input type="text"
                                       name="last_name"
                                       id="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}"
                                       required>
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Пароль</label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       class="form-control"
                                       required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="{{ route('login.form') }}">Уже есть аккаунт? Войти</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
