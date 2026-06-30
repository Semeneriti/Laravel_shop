<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ProductFilterDto;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function getProducts(ProductFilterDto $dto): LengthAwarePaginator
    {
        $query = Product::query();

        return $this->applyFiltersAndSort($query, $dto);
    }

    public function getProductsByCategoryId(int $categoryId, ProductFilterDto $dto): LengthAwarePaginator
    {
        $query = Product::query()
            ->where('category_id', $categoryId);

        return $this->applyFiltersAndSort($query, $dto);
    }

    private function applyFiltersAndSort($query, ProductFilterDto $dto): LengthAwarePaginator
    {
        // Поиск по названию
        if ($dto->q) {
            $q = $dto->q;
            $query->where(function ($subQuery) use ($q) {
                $subQuery
                    ->where('name', 'like', '%' . $q . '%')
                    ->orWhere('sku', 'like', '%' . $q . '%');
            });
        }

        // Цена: от
        if ($dto->min_price !== null) {
            $query->where('price', '>=', $dto->min_price);
        }

        // Цена: до
        if ($dto->max_price !== null) {
            $query->where('price', '<=', $dto->max_price);
        }

        // В наличии
        if ($dto->in_stock) {
            $query->where('stock', '>', 0);
        }

        // Сортировка
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

        return $query->paginate($dto->per_page)->withQueryString();
    }

    public function getMaxProductPrice(): int
    {
        return (int) (Product::query()->max('price') ?? 0);
    }
}
