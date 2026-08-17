<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\CartDto;
use App\Http\Requests\CartRequest;
use App\Models\Product;
use App\Services\SessionCartService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private readonly SessionCartService $sessionCartService,
    ) {
    }

    public function index(): Factory|View
    {
        $items = $this->sessionCartService->getItems();
        $totalQuantity = $this->sessionCartService->getTotalQuantity();
        $totalPrice = $this->sessionCartService->getTotalPrice();

        $defaultAddress = null;
        if (Auth::check()) {
            $defaultAddress = Auth::user()
                ->addresses()
                ->where('is_default', true)
                ->first();
        }

        return view('cart.index', compact('items', 'totalQuantity', 'totalPrice', 'defaultAddress'));
    }

    public function store(Product $product, CartRequest $request): JsonResponse|RedirectResponse
    {
        $dto = CartDto::fromRequest($request);
        $this->sessionCartService->add($product, $dto->quantity);

        return $this->respond($request);
    }

    public function update(Product $product, CartRequest $request): JsonResponse|RedirectResponse
    {
        $dto = CartDto::fromRequest($request);
        $this->sessionCartService->setQuantity($product, $dto->quantity);

        return $this->respond($request);
    }

    public function destroy(Product $product, Request $request): JsonResponse|RedirectResponse
    {
        $this->sessionCartService->remove($product);

        return $this->respond($request);
    }

    public function clear(Request $request): JsonResponse|RedirectResponse
    {
        $this->sessionCartService->clear();

        return $this->respond($request);
    }

    private function respond(Request $request): JsonResponse|RedirectResponse
    {
        $cartCount = $this->sessionCartService->getTotalQuantity();

        $payload = [
            'cartCount' => $cartCount,
        ];

        if ($request->expectsJson()) {
            $payload['html'] = view('cart._content', [
                'items' => $this->sessionCartService->getItems(),
                'totalQuantity' => $this->sessionCartService->getTotalQuantity(),
                'totalPrice' => $this->sessionCartService->getTotalPrice(),
            ])->render();

            return response()->json($payload);
        }

        return redirect()
            ->back()
            ->with('cartCount', $payload['cartCount'])
            ->with('success', 'Корзина обновлена!');
    }
}
