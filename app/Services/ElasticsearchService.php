<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ElasticsearchService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('elasticsearch.hosts.0', 'http://elasticsearch:9200');
    }

    public function createProductsIndex(): void
    {
        Http::delete($this->baseUrl . '/products');

        Http::put($this->baseUrl . '/products', [
            'mappings' => [
                'properties' => [
                    'name' => ['type' => 'text'],
                    'description' => ['type' => 'text'],
                    'price' => ['type' => 'float'],
                    'sku' => ['type' => 'keyword'],
                    'stock' => ['type' => 'integer'],
                    'status' => ['type' => 'keyword'],
                    'created_at' => ['type' => 'date'],
                ],
            ],
        ]);
    }

    public function indexProduct(array $data): void
    {
        Http::put($this->baseUrl . '/products/_doc/' . $data['id'], $data);
    }

    public function searchProducts(string $query, array $filters = []): array
    {
        $must = [];

        if (!empty($query)) {
            $must[] = [
                'multi_match' => [
                    'query' => $query,
                    'fields' => ['name^2', 'description', 'sku'],
                ],
            ];
        }

        $filter = [];
        if (!empty($filters['min_price'])) {
            $filter[] = ['range' => ['price' => ['gte' => $filters['min_price']]]];
        }
        if (!empty($filters['max_price'])) {
            $filter[] = ['range' => ['price' => ['lte' => $filters['max_price']]]];
        }
        if (!empty($filters['in_stock'])) {
            $filter[] = ['range' => ['stock' => ['gt' => 0]]];
        }

        $response = Http::post($this->baseUrl . '/products/_search', [
            'query' => [
                'bool' => [
                    'must' => $must,
                    'filter' => $filter,
                ],
            ],
            'sort' => [
                ['_score' => ['order' => 'desc']],
                ['id' => ['order' => 'desc']],
            ],
            'from' => 0,
            'size' => 100,
        ]);

        return $response->json()['hits']['hits'] ?? [];
    }
}
