@extends('layouts.app')

@section('title', 'Управление заказами')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Заказы</h1>
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Создать заказ</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                        <td>{{ number_format($order->total, 0, ',', ' ') }} ₽</td>
                        <td><span class="badge bg-secondary">{{ $order->status_label }}</span></td>
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">Просмотр</a>
                            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-primary">Редактировать</a>
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить заказ?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
