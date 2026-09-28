<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\PaymentReceipt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class YooKassaPaymentService
{
    private string $baseUrl;
    private string $shopId;
    private string $secretKey;
    private string $currency;

    public function __construct()
    {
        $this->baseUrl = config('services.yookassa.base_url');
        $this->shopId = config('services.yookassa.shop_id');
        $this->secretKey = config('services.yookassa.secret_key');
        $this->currency = config('services.yookassa.currency', 'RUB');
    }

    public function createPaymentForOrder(Order $order, array $receiptItems = []): OrderPayment
    {
        $idempotenceKey = (string) Str::uuid();

        $payload = [
            'amount' => [
                'value' => number_format((float) $order->total, 2, '.', ''),
                'currency' => $this->currency,
            ],
            'capture' => true,
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => route('payments.yookassa.return', $order),
            ],
            'description' => 'Оплата заказа #' . $order->id,
            'metadata' => [
                'order_id' => $order->id,
            ],
        ];

        if (! empty($receiptItems) && config('services.yookassa.receipts.enabled', true)) {
            $payload['receipt'] = $this->buildReceipt($order, $receiptItems);
        }

        $response = Http::withBasicAuth($this->shopId, $this->secretKey)
            ->withHeaders([
                'Idempotence-Key' => $idempotenceKey,
                'Content-Type' => 'application/json',
            ])
            ->post($this->baseUrl . '/payments', $payload);

        $responseData = $response->json();

        if (! $response->successful()) {
            throw new \Exception($responseData['description'] ?? 'Ошибка создания платежа');
        }

        return OrderPayment::create([
            'order_id' => $order->id,
            'provider' => 'yookassa',
            'status' => $responseData['status'],
            'amount' => $order->total,
            'currency' => $this->currency,
            'external_payment_id' => $responseData['id'],
            'idempotence_key' => $idempotenceKey,
            'confirmation_url' => $responseData['confirmation']['confirmation_url'] ?? null,
            'request_payload' => $payload,
            'response_payload' => $responseData,
        ]);
    }

    public function fetchPayment(string $paymentId): array
    {
        $response = Http::withBasicAuth($this->shopId, $this->secretKey)
            ->get($this->baseUrl . '/payments/' . $paymentId);

        if (! $response->successful()) {
            throw new \Exception('Не удалось получить статус платежа');
        }

        return $response->json();
    }

    public function synchronizePayment(OrderPayment $payment): OrderPayment
    {
        $data = $this->fetchPayment($payment->external_payment_id);

        $payment->update([
            'status' => $data['status'],
            'response_payload' => $data,
            'paid_at' => $data['paid_at'] ?? null,
            'canceled_at' => $data['canceled_at'] ?? null,
        ]);

        return $payment;
    }

    private function buildReceipt(Order $order, array $items): array
    {
        $receiptItems = [];
        foreach ($items as $item) {
            $receiptItems[] = [
                'description' => $item['description'] ?? 'Товар',
                'quantity' => $item['quantity'] ?? 1,
                'amount' => [
                    'value' => number_format((float) $item['price'], 2, '.', ''),
                    'currency' => $this->currency,
                ],
                'vat_code' => config('services.yookassa.receipts.vat_code', 1),
                'payment_mode' => config('services.yookassa.receipts.payment_mode', 'full_payment'),
                'payment_subject' => config('services.yookassa.receipts.payment_subject', 'commodity'),
            ];
        }

        return [
            'customer' => [
                'email' => $order->user->email,
            ],
            'items' => $receiptItems,
            'tax_system_code' => config('services.yookassa.receipts.tax_system_code', 1),
        ];
    }

    public function createReceipt(OrderPayment $payment, array $items): PaymentReceipt
    {
        $payload = [
            'payment_id' => $payment->external_payment_id,
            'type' => 'payment',
            'send' => true,
            'customer' => [
                'email' => $payment->order->user->email,
            ],
            'items' => $items,
            'tax_system_code' => config('services.yookassa.receipts.tax_system_code', 1),
        ];

        $response = Http::withBasicAuth($this->shopId, $this->secretKey)
            ->post($this->baseUrl . '/receipts', $payload);

        $data = $response->json();

        return PaymentReceipt::create([
            'order_payment_id' => $payment->id,
            'external_receipt_id' => $data['id'] ?? null,
            'type' => 'payment',
            'status' => $data['status'] ?? 'pending',
            'send_to_customer' => $data['send'] ?? false,
            'request_payload' => $payload,
            'response_payload' => $data,
            'error_message' => $data['description'] ?? null,
        ]);
    }
}
