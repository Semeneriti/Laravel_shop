<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class SalesReportService
{
    public function getLastWeekReport(): array
    {
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays(6)->startOfDay();

        // Всего заказов за неделю
        $ordersCount = Order::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Успешных продаж (paid, shipped, completed)
        $salesCount = Order::query()
            ->whereIn('status', [
                Order::STATUS_PAID,
                Order::STATUS_SHIPPED,
                Order::STATUS_COMPLETED,
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Выручка
        $revenue = Order::query()
            ->whereIn('status', [
                Order::STATUS_PAID,
                Order::STATUS_SHIPPED,
                Order::STATUS_COMPLETED,
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        // Отменённые заказы
        $canceledCount = Order::query()
            ->where('status', Order::STATUS_CANCELED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Продажи по дням
        $dailySales = $this->getDailySales($startDate, $endDate);

        return [
            'ordersCount' => $ordersCount,
            'salesCount' => $salesCount,
            'revenue' => $revenue,
            'canceledCount' => $canceledCount,
            'dailySales' => $dailySales,
        ];
    }

    private function getDailySales(Carbon $startDate, Carbon $endDate): array
    {
        $days = [];
        $current = clone $startDate;

        while ($current <= $endDate) {
            $date = $current->copy();

            $sales = Order::query()
                ->whereIn('status', [
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED,
                ])
                ->whereDate('created_at', $date)
                ->count();

            $revenue = Order::query()
                ->whereIn('status', [
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED,
                ])
                ->whereDate('created_at', $date)
                ->sum('total');

            $days[] = [
                'date' => $date->format('d.m.Y'),
                'sales' => $sales,
                'revenue' => $revenue,
            ];

            $current->addDay();
        }

        return $days;
    }
}
