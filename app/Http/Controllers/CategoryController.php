<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\ProductFilterDto;
use App\Http\Requests\ProductFilterRequest;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function index(): Factory|View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category, ProductFilterRequest $request): Factory|View
    {
        $dto = ProductFilterDto::fromRequest($request);

        $products = $this->productService->getProductsByCategoryId($category->id, $dto);
        $maxProductPrice = $this->productService->getMaxProductPrice();

        return view('products.index', [
            'products' => $products,
            'category' => $category,
            'dto'      => $dto,
            'maxProductPrice' => $maxProductPrice,
        ]);
    }
}
