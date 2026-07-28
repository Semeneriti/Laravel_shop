<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminOrderStoreRequest;
use App\Http\Requests\AdminOrderUpdateRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\AdminOrderService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly AdminOrderService $orderService,
    ) {}

    public function index(): Factory|View
    {
        $orders = Order::with(['user', 'items.product'])->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function create(): Factory|View
    {
        $users = User::all();
        $products = Product::all();

        return view('admin.orders.create', compact('users', 'products'));
    }

    public function store(AdminOrderStoreRequest $request): RedirectResponse
    {
        $this->orderService->create($request->validated());

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Заказ создан!');
    }

    public function show(Order $order): Factory|View
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order): Factory|View
    {
        $users = User::all();
        $products = Product::all();

        return view('admin.orders.edit', compact('order', 'users', 'products'));
    }

    public function update(AdminOrderUpdateRequest $request, Order $order): RedirectResponse
    {
        $this->orderService->update($order, $request->validated());

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Заказ обновлён!');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $this->orderService->delete($order);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Заказ удалён!');
    }
}
