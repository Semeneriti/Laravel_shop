<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ProductDto;
use App\DTO\ProductFilterDto;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    // ===== КАТАЛОГ =====
    public function getProducts(ProductFilterDto $dto): LengthAwarePaginator
    {
        $query = Product::query();

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

        $query->orderByDesc('id');

        $perPage = in_array($dto->per_page, [10, 25, 50, 100], true)
            ? $dto->per_page
            : 10;

        return $query->paginate($perPage)->withQueryString();
    }

    public function getMaxProductPrice(): int
    {
        return (int) (Product::query()->max('price') ?? 0);
    }

    public function getProductsByCategoryId(int $categoryId, ProductFilterDto $dto): LengthAwarePaginator
    {
        $query = Product::query()
            ->where('category_id', $categoryId);

        return $this->getProducts($dto);
    }

    // ===== АДМИНКА (CRUD) =====

    public function create(ProductDto $dto): Product
    {
        $data = $dto->toProductData();

        if ($dto->image) {
            $data['image'] = $dto->image->store('products', 'public');
        }

        return Product::create($data);
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

        return $product;
    }

    public function delete(Product $product): void
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
    }
}
