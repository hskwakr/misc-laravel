<?php

namespace Tests\Feature\View\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_contains_empty_table(): void
    {
        $response = $this->get('/products', []);

        $response->assertStatus(200);
        // $response->assertSee(__('No products found'));
    }

    public function test_homepage_contains_non_empty_table(): void
    {
        Product::create([
            'name'  => 'Product 1',
            'price' => 123,
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
    }
}
