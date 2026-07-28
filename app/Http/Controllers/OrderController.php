<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\SessionCartService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    public function index(): Factory|View
    {
        $orders = Order::query()
            ->where('user_id', Auth::id())
            ->with(['items.product'])
            ->orderByDesc('created_at')
            ->get();

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    public function store(
        OrderStoreRequest $request,
        SessionCartService $cart
    ): RedirectResponse {
        $user = Auth::user();

        $this->orderService->createOrder(
            $user,
            $request->validated()['payment_method'],
            $cart
        );

        return redirect()
            ->route('orders.index')
            ->with('success', 'Заказ создан.');
    }

    public function updateStatus(
        Order $order,
        OrderStatusRequest $request
    ): RedirectResponse {
        $order = Order::query()
            ->where('user_id', Auth::id())
            ->whereKey($order->id)
            ->firstOrFail();

        $status = $request->validated()['status'];

        if ($status === Order::STATUS_PAID) {
            $this->orderService->markAsPaid($order);
            $message = 'Заказ оплачен.';
        } else {
            $this->orderService->cancel($order);
            $message = 'Заказ отменен.';
        }

        return redirect()
            ->route('orders.index')
            ->with('success', $message);
    }
}
