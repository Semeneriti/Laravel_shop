@extends('layouts.app')

@section('title', 'Управление товарами')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Товары</h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Создать товар</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Статус</th>
                    <th>Категория</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->price, 0, ',', ' ') }} ₽</td>
                        <td><span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ $product->status_label }}</span></td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">Просмотр</a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">Редактировать</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить товар?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
