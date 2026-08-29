<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\SalesReportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSalesReportsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public array $backoff = [30, 60, 120];

    public function __construct()
    {
        $this->onQueue('orders.reports.sales');
    }

    public function handle(SalesReportService $salesReportService): void
    {
        $salesReportService->refreshRecentReports();
    }
}
