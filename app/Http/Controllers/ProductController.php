<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\ProductFilterDto;
use App\Http\Requests\ProductFilterRequest;
use App\Models\Product;
use App\Services\ElasticsearchService;
use App\Services\ProductService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ElasticsearchService $elasticsearchService,
    ) {
    }

    public function index(ProductFilterRequest $request): Factory|View
    {
        $dto = ProductFilterDto::fromRequest($request);

        $searchResults = $this->elasticsearchService->searchProducts(
            $dto->q ?? '',
            [
                'min_price' => $dto->min_price,
                'max_price' => $dto->max_price,
                'in_stock' => $dto->in_stock,
            ]
        );

        $productIds = collect($searchResults)->pluck('_id')->map(fn ($id) => (int) $id)->toArray();

        if (empty($productIds)) {
            $products = Product::query()->whereRaw('1 = 0')->paginate($dto->per_page);
        } else {
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->paginate($dto->per_page);
        }

        $products->appends($request->query());

        $maxProductPrice = $this->productService->getMaxProductPrice();

        return view('products.index', compact('products', 'dto', 'maxProductPrice'));
    }

    public function show(Product $product): Factory|View
    {
        return view('products.show', compact('product'));
    }
}
