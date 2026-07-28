@extends('layouts.app')

@section('title', 'Редактировать товар')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-3">Редактировать товар</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.products._form')

            <button type="submit" class="btn btn-primary">Обновить</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
