<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\ProductDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    public function index(): Factory|View
    {
        $products = Product::with('category')->get();

        return view('admin.products.index', compact('products'));
    }

    public function create(): Factory|View
    {
        $categories = \App\Models\Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductStoreRequest $request): RedirectResponse
    {
        $dto = ProductDto::fromRequest($request);

        $this->productService->create($dto);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Товар создан!');
    }

    public function show(Product $product): Factory|View
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): Factory|View
    {
        $categories = \App\Models\Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $dto = ProductDto::fromRequest($request);

        $this->productService->update($product, $dto);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Товар обновлён!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->delete($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Товар удалён!');
    }
}
