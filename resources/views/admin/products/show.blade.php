@extends('layouts.app')

@section('title', ->name)

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">{{ $product->name }}</h1>
            <div>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">Редактировать</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Назад</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
                @else
                    <div class="bg-light p-5 text-center">Нет изображения</div>
                @endif
            </div>
            <div class="col-md-8">
                <p><strong>Цена:</strong> {{ number_format($product->price, 0, ',', ' ') }} ₽</p>
                <p><strong>Артикул:</strong> {{ $product->sku }}</p>
                <p><strong>Количество:</strong> {{ $product->stock }}</p>
                <p><strong>Статус:</strong> <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $product->status_label }}</span></p>
                <p><strong>Категория:</strong> {{ $product->category?->name ?? 'Без категории' }}</p>
                <p><strong>Описание:</strong> {{ $product->description ?? 'Нет описания' }}</p>
            </div>
        </div>
    </div>
@endsection
