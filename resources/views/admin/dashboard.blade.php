@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-2">Админ-панель</h1>
        <p class="text-muted mb-4">
            Статистика за последние 7 дней
            @if($report['calculated_at'])
                <span class="text-muted ms-3">
                    (обновлено: {{ $report['calculated_at']->format('d.m.Y H:i') }})
                </span>
            @endif
        </p>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h6 class="card-title">Всего заказов</h6>
                        <p class="display-6">{{ $report['orders_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h6 class="card-title">Продаж</h6>
                        <p class="display-6">{{ $report['sales_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-bg-warning">
                    <div class="card-body">
                        <h6 class="card-title">Выручка</h6>
                        <p class="display-6">{{ number_format($report['revenue'], 0, ',', ' ') }} ₽</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-bg-danger">
                    <div class="card-body">
                        <h6 class="card-title">Отменено</h6>
                        <p class="display-6">{{ $report['canceled_count'] }}</p>
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
                                <th>Заказов</th>
                                <th>Продаж</th>
                                <th>Выручка</th>
                                <th>Отменено</th>
                                <th>Обновлено</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report['daily_reports'] as $dailyReport)
                                <tr>
                                    <td>{{ $dailyReport->report_date->format('d.m.Y') }}</td>
                                    <td>{{ $dailyReport->orders_count }}</td>
                                    <td>{{ $dailyReport->sales_count }}</td>
                                    <td>{{ number_format((float) $dailyReport->revenue, 0, ',', ' ') }} ₽</td>
                                    <td>{{ $dailyReport->canceled_count }}</td>
                                    <td>{{ $dailyReport->calculated_at?->format('d.m.Y H:i') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Отчёты ещё не сформированы. Запустите Scheduler и queue worker.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
