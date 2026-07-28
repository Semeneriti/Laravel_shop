@extends('layouts.app')

@section('title', 'Создать товар')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-3">Создать товар</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            @include('admin.products._form')

            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
