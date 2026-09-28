<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ElasticsearchService;
use Illuminate\Console\Command;

class ElasticsearchReindex extends Command
{
    protected $signature = 'elasticsearch:reindex';

    protected $description = 'Пересоздаёт индекс и загружает все товары';

    public function handle(ElasticsearchService $service): int
    {
        $this->info('Создаём индекс...');
        $service->createProductsIndex();

        $this->info('Индексируем товары...');
        Product::chunk(100, function ($products) use ($service) {
            foreach ($products as $product) {
                $service->indexProduct($product->toSearchableArray());
            }
        });

        $this->info('Готово!');

        return Command::SUCCESS;
    }
}
