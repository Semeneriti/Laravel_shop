<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPageTest extends TestCase
{
    use RefreshDatabase;

    // ========== УРОВЕНЬ 1: Доступность ==========

    public function test_products_page_is_accessible(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
    }

    // ========== УРОВЕНЬ 2: Рендеринг ==========

    public function test_product_page_is_accessible(): void
    {
        $product = Product::factory()->create();

        $response = $this->get('/products/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    // ========== УРОВЕНЬ 3: Данные ==========

    public function test_product_page_shows_category(): void
    {
        $category = Category::create([
            'name' => 'Смартфоны',
            'slug' => 'smartfony',
        ]);

        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->get('/products/' . $product->id);

        $response->assertSee('Смартфоны');
    }

    // ========== УРОВЕНЬ 4: Действия ==========

    public function test_admin_can_create_product(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Новый товар',
            'price' => 1500,
            'stock' => 10,
            'sku' => 'SKU-NEW-001',
            'status' => 'active',
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Новый товар',
            'sku' => 'SKU-NEW-001',
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $admin = $this->createAdmin();
        $product = Product::factory()->create(['name' => 'Старое имя']);

        $response = $this->actingAs($admin)->put('/admin/products/' . $product->id, [
            'name' => 'Новое имя',
            'price' => $product->price,
            'stock' => $product->stock,
            'sku' => $product->sku,
            'status' => 'active',
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Новое имя',
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $admin = $this->createAdmin();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->delete('/admin/products/' . $product->id);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    // ========== ВСПОМОГАТЕЛЬНЫЙ МЕТОД ==========

    private function createAdmin(): User
    {
        $user = User::factory()->create();

        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Администратор']
        );

        $user->roles()->attach($adminRole);

        return $user;
    }
}
