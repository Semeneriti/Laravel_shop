<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\ProductFilterDto;
use App\Http\Requests\ProductFilterRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function index(ProductFilterRequest $request): Factory|View
    {
        $dto = ProductFilterDto::fromRequest($request);
        $products = $this->productService->getProducts($dto);
        $maxProductPrice = $this->productService->getMaxProductPrice();

        return view('products.index', compact('products', 'dto', 'maxProductPrice'));
    }

    public function show(Product $product): Factory|View
    {
        return view('products.show', compact('product'));
    }
}
