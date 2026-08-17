@extends('layouts.app')

@section('title', 'Создать заказ')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-3">Создать заказ</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf

            @include('admin.orders._form')

            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
