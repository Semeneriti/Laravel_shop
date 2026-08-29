<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DailySalesReport;
use App\Models\Order;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class SalesReportService
{
    private const REPORT_DAYS = 7;

    private const SUCCESSFUL_ORDER_STATUSES = [
        Order::STATUS_PAID,
        Order::STATUS_SHIPPED,
        Order::STATUS_COMPLETED,
    ];

    public function refreshRecentReports(): void
    {
        for ($daysAgo = 0; $daysAgo < self::REPORT_DAYS; $daysAgo++) {
            $date = now()
                ->startOfDay()
                ->subDays($daysAgo);

            $this->refreshReportForDate($date);
        }
    }

    public function refreshReportForDate(CarbonInterface $date): DailySalesReport
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $ordersQuery = Order::query()
            ->whereBetween('created_at', [
                $startOfDay,
                $endOfDay,
            ]);

        $ordersCount = (clone $ordersQuery)->count();

        $salesCount = (clone $ordersQuery)
            ->whereIn('status', self::SUCCESSFUL_ORDER_STATUSES)
            ->count();

        $revenue = (clone $ordersQuery)
            ->whereIn('status', self::SUCCESSFUL_ORDER_STATUSES)
            ->sum('total');

        $canceledCount = (clone $ordersQuery)
            ->where('status', Order::STATUS_CANCELED)
            ->count();

        return DailySalesReport::query()->updateOrCreate(
            [
                'report_date' => $date->toDateString(),
            ],
            [
                'orders_count' => $ordersCount,
                'sales_count' => $salesCount,
                'revenue' => $revenue,
                'canceled_count' => $canceledCount,
                'calculated_at' => now(),
            ]
        );
    }

    public function getRecentReports(): Collection
    {
        return DailySalesReport::query()
            ->whereDate(
                'report_date',
                '>=',
                now()->subDays(self::REPORT_DAYS - 1)->toDateString()
            )
            ->orderBy('report_date')
            ->get();
    }

    public function getDashboardReport(): array
    {
        $reports = $this->getRecentReports();

        return [
            'orders_count' => $reports->sum('orders_count'),
            'sales_count' => $reports->sum('sales_count'),
            'revenue' => $reports->sum(
                fn (DailySalesReport $report): float =>
                    (float) $report->revenue
            ),
            'canceled_count' => $reports->sum('canceled_count'),
            'daily_reports' => $reports,
            'calculated_at' => $reports->max('calculated_at'),
        ];
    }
}
