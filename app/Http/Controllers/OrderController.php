<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\YooKassaPaymentService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly YooKassaPaymentService $yookassaService,
    ) {
    }

    public function index(): Factory|View
    {
        $orders = Order::query()
            ->where('user_id', Auth::id())
            ->with(['items.product', 'payments'])
            ->orderByDesc('created_at')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function store(OrderStoreRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $order = $this->orderService->createOrder(
            $user,
            $request->validated()
        );

        if ($request->payment_method === Order::PAYMENT_METHOD_YOOKASSA) {
            try {
                $payment = $this->yookassaService->createPaymentForOrder($order);
                return redirect()->away($payment->confirmation_url);
            } catch (\Exception $e) {
                return redirect()->route('orders.index')
                    ->with('error', 'Ошибка создания платежа: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('orders.index')
            ->with('success', 'Заказ создан!');
    }
}
