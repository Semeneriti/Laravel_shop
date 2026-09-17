<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

trait DatabaseMigrationsWithTypes
{
    use DatabaseMigrations;

    protected function setUpTraits(): void
    {
        parent::setUpTraits();

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('DROP TYPE IF EXISTS cart_status CASCADE');
            DB::statement('DROP TYPE IF EXISTS order_status CASCADE');
        }
    }
}
