<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\OrderService;
use App\Services\YooKassaPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class YooKassaController extends Controller
{
    public function __construct(
        private readonly YooKassaPaymentService $paymentService,
        private readonly OrderService $orderService,
    ) {
    }

    public function return(Request $request, Order $order): RedirectResponse
    {
        $payment = $order->latestPayment();

        if (! $payment) {
            return redirect()->route('orders.index')
                ->with('error', 'Платёж не найден.');
        }

        try {
            $this->paymentService->synchronizePayment($payment);

            if ($payment->status === 'succeeded') {
                $this->orderService->markAsPaid($order);
                return redirect()->route('orders.index')
                    ->with('success', 'Оплата прошла успешно!');
            }

            return redirect()->route('orders.index')
                ->with('info', 'Статус оплаты: ' . $payment->status);

        } catch (\Exception $e) {
            Log::error('YooKassa return error: ' . $e->getMessage());
            return redirect()->route('orders.index')
                ->with('error', 'Ошибка проверки оплаты.');
        }
    }

    public function webhook(Request $request): \Illuminate\Http\Response
    {
        $payload = $request->all();

        Log::info('YooKassa webhook received', $payload);

        $paymentId = $payload['object']['id'] ?? null;

        if (! $paymentId) {
            return response('Payment id not found', 400);
        }

        $localPayment = OrderPayment::where('external_payment_id', $paymentId)->first();

        if (! $localPayment) {
            Log::warning('Payment not found in local DB', ['payment_id' => $paymentId]);
            return response('Payment not found', 404);
        }

        try {
            $this->paymentService->synchronizePayment($localPayment);

            if ($localPayment->status === 'succeeded') {
                $this->orderService->markAsPaid($localPayment->order);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return response('Error processing webhook', 500);
        }
    }
}
