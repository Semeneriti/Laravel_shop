<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

trait RefreshDatabaseWithTypes
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dropPostgresTypes();
    }

    private function dropPostgresTypes(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('DROP TYPE IF EXISTS cart_status CASCADE');
        DB::statement('DROP TYPE IF EXISTS order_status CASCADE');
    }
}
