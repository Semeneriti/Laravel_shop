@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-3">Админ-панель</h1>
        <p class="text-muted">Статистика за последние 7 дней</p>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Всего заказов</h5>
                        <p class="display-6">{{ $report['ordersCount'] }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Продаж</h5>
                        <p class="display-6">{{ $report['salesCount'] }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Выручка</h5>
                        <p class="display-6">{{ number_format($report['revenue'], 0, ',', ' ') }} ₽</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Отменено</h5>
                        <p class="display-6">{{ $report['canceledCount'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Продажи по дням</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Количество продаж</th>
                                <th>Выручка</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report['dailySales'] as $day)
                                <tr>
                                    <td>{{ $day['date'] }}</td>
                                    <td>{{ $day['sales'] }}</td>
                                    <td>{{ number_format($day['revenue'], 0, ',', ' ') }} ₽</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
