<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProductTest extends TestCase
{
    use DatabaseMigrations;


    //GUEST TESTS

    public function test_guest_cannot_see_products_page()
    {
        $response = $this->get('/product');

        $response->assertStatus(302);
    }

    public function test_guest_cannot_see_edit_products_page()
    {
        $product = Product::factory()->create();
        $id = $product->id;
        $response = $this->get("/product/$id/edit");

        $response->assertStatus(302);
    }

    public function test_guest_cannot_create_product()
    {
        $data = [
            'name' => 'Notebook',
            'price' => 3500.00,
            'SKU' => 'NTBK',
            'description' => 'idk'
        ];
        $response = $this->post('/product', $data);
        $products = Product::all();
        $this->assertEmpty($products);
    }

    public function test_guest_cannot_update_product()
    {
        $product = Product::factory()->create();
        $id = $product->id;
        $data = [
            'name' => 'JUST TESTING',
            'price' => 10000.00,
        ];
        $this->put("/product/$id", $data);
        $newProduct = Product::find($id);
        $this->assertNotEquals($newProduct->name, $data['name']);
        $this->assertNotEquals($newProduct->price, $data['price']);
    }

    public function test_guest_cannot_delete_product()
    {
        $product = Product::factory()->create();
        $id = $product->id;

        $this->delete("/product/$id");
        $products = Product::all();
        $this->assertNotEmpty($products);
    }

    // USER TESTS

    public function test_index_page_shows_created_products()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->get('/product');

        $response->assertSeeText($product->name);
    }

    public function test_user_can_access_product_creation_page()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/product/create');

        $response->assertStatus(200);
    }

    public function test_user_can_access_product_edit_page()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $id = $product->id;

        $response = $this->actingAs($user)->get("/product/$id/edit");

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    //STORE
    public function test_user_can_create_product()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $data = [
            'name' => 'Notebook',
            'price' => 3500.00,
            'SKU' => 'NTBK',
            'description' => 'idk'
        ];
        $response = $this->actingAs($user)->post('/product', $data);

        $response->assertRedirect('/product');

        $products = Product::all();
        $this->assertNotEmpty($products);
    }


    //UPDATE
    public function test_user_can_update_product()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $id = $product->id;
        $data = [
            'name' => 'JUST TESTING',
            'price' => 10000.00,
        ];
        $response = $this->actingAs($user)->put("/product/$id", $data);
        $newProduct = Product::findOrFail($id);
        $this->assertEquals($newProduct->name, $data['name']);
        $this->assertEquals($newProduct->price, $data['price']);
        $this->assertNotEquals($product, $newProduct);
    }


    //DELETE
    public function test_user_can_delete_product()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $id = $product->id;

        $this->actingAs($user)->delete("/product/$id");
        $products = Product::all();
        $this->assertEmpty($products);
    }

    public function test_a_stock_table_is_created_each_time_the_product_is_created()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();

        $data = [
            'name' => 'Notebook',
            'price' => 3500.00,
            'SKU' => 'NTBK',
            'description' => 'idk'
        ];
        $response = $this->actingAs($user)->post('/product', $data);
        $stock = Stock::where('product_id', $data['SKU'])->first();
        $this->assertNotNull($stock);
        $this->assertEquals($stock->product_id, $data['SKU']);
    }
}
