@extends('layouts.app')

@section('title', 'Заказ #' . $order->id)

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-3">Заказ #{{ $order->id }}</h1>

        <div class="card">
            <div class="card-body">
                <p><strong>Пользователь:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Статус:</strong> <span class="badge bg-secondary">{{ $order->status_label }}</span></p>
                <p><strong>Способ оплаты:</strong> {{ $order->payment_method_label }}</p>
                <p><strong>Адрес доставки:</strong> {{ $order->shipping_address ?? '—' }}</p>
                <p><strong>Сумма:</strong> <strong>{{ number_format($order->total, 0, ',', ' ') }} ₽</strong></p>
                <p><strong>Дата создания:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
        </div>

        <h4 class="mt-4">Товары в заказе</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Товар удалён' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, ',', ' ') }} ₽</td>
                        <td>{{ number_format($item->quantity * $item->price, 0, ',', ' ') }} ₽</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Итого:</th>
                    <th>{{ number_format($order->total, 0, ',', ' ') }} ₽</th>
                </tr>
            </tfoot>
        </table>

        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">Редактировать</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
