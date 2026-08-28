<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SalesReportService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(SalesReportService $salesReportService): Factory|View
    {
        $report = $salesReportService->getLastWeekReport();

        return view('admin.dashboard', compact('report'));
    }
}
