<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ProductDto;
use App\DTO\ProductFilterDto;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    private const CATALOG_CACHE_TTL = 600;

    private const CATALOG_VERSION_KEY = 'products:catalog:version';

    public function getProducts(ProductFilterDto $dto): LengthAwarePaginator
    {
        $version = $this->getCatalogVersion();
        $key = $this->buildCatalogKey($dto, $version);

        return Cache::remember($key, self::CATALOG_CACHE_TTL, function () use ($dto) {
            return $this->buildCatalogQuery($dto)->paginate($dto->per_page);
        });
    }

    public function getMaxProductPrice(): int
    {
        $version = $this->getCatalogVersion();
        $key = "products:max_price:v{$version}";

        return Cache::remember($key, self::CATALOG_CACHE_TTL, function () {
            return (int) (Product::query()->max('price') ?? 0);
        });
    }

    public function getProductsByCategoryId(int $categoryId, ProductFilterDto $dto): LengthAwarePaginator
    {
        $version = $this->getCatalogVersion();
        $key = $this->buildCatalogKey($dto, $version, $categoryId);

        return Cache::remember($key, self::CATALOG_CACHE_TTL, function () use ($categoryId, $dto) {
            return $this->buildCatalogQuery($dto, $categoryId)->paginate($dto->per_page);
        });
    }

    public function create(ProductDto $dto): Product
    {
        $data = $dto->toProductData();

        if ($dto->image) {
            $data['image'] = $dto->image->store('products', 'public');
        }

        $product = Product::create($data);
        $this->invalidateCatalogCache();

        return $product;
    }

    public function update(Product $product, ProductDto $dto): Product
    {
        $data = $dto->toProductData();

        if ($dto->image) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $dto->image->store('products', 'public');
        }

        $product->update($data);
        $this->invalidateCatalogCache();

        return $product;
    }

    public function delete(Product $product): void
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        $this->invalidateCatalogCache();
    }

    private function buildCatalogQuery(ProductFilterDto $dto, ?int $categoryId = null)
    {
        $query = Product::query();

        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }

        if ($dto->q) {
            $q = $dto->q;
            $query->where(function ($subQuery) use ($q) {
                $subQuery
                    ->where('name', 'like', '%' . $q . '%')
                    ->orWhere('sku', 'like', '%' . $q . '%');
            });
        }

        if ($dto->min_price !== null) {
            $query->where('price', '>=', $dto->min_price);
        }

        if ($dto->max_price !== null) {
            $query->where('price', '<=', $dto->max_price);
        }

        if ($dto->in_stock) {
            $query->where('stock', '>', 0);
        }

        switch ($dto->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'new':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        return $query->orderByDesc('id');
    }

    private function buildCatalogKey(ProductFilterDto $dto, int $version, ?int $categoryId = null): string
    {
        $params = [
            'category' => $categoryId,
            'page' => request()->get('page', 1),
            'per_page' => $dto->per_page,
            'q' => $dto->q,
            'min_price' => $dto->min_price,
            'max_price' => $dto->max_price,
            'in_stock' => $dto->in_stock,
            'sort' => $dto->sort,
        ];

        $hash = hash('sha256', json_encode($params));

        return "products:catalog:v{$version}:{$hash}";
    }

    private function getCatalogVersion(): int
    {
        return (int) Cache::get(self::CATALOG_VERSION_KEY, 1);
    }

    private function invalidateCatalogCache(): void
    {
        Cache::increment(self::CATALOG_VERSION_KEY);
    }
}
