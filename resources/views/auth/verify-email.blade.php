@extends('layouts.app')

@section('title', 'Подтвердите email')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Подтвердите email</div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('info'))
                            <div class="alert alert-info">{{ session('info') }}</div>
                        @endif

                        <p>Мы отправили письмо с ссылкой для подтверждения на ваш email.</p>
                        <p>Если вы не получили письмо, нажмите кнопку ниже.</p>

                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Отправить повторно</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
