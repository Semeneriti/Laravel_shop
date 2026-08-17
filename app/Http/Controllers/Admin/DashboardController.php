<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): Factory|View
    {
        return view('admin.dashboard');
    }
}
