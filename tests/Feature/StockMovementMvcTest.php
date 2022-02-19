<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class StockMovementMvcTest extends TestCase
{
    use DatabaseMigrations;


    //GUEST TESTS

    //guest não consegue ver página de remoção
    public function test_guest_cannot_see_the_remove_products_from_stock_page()
    {
        $product = $this->createProductAndStock();
        $response = $this->get('/baixar-produtos/1');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
    //guest não consegue ver página de adição
    public function test_guest_cannot_see_add_products_to_stock_page()
    {
        $product = $this->createProductAndStock();
        $response = $this->get('/adicionar-produtos/1');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
    //guest não consegue adicionar produto
    public function test_guest_cannot_add_stock()
    {
        $product = $this->createProductAndStock();

        $oldStockQuantity = $product->stock->products_in_stock; //100
        $payload = ['quantity' => 200];
        $response = $this->post('/adicionar-produtos/1', $payload);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEquals($oldStockQuantity, $product->stock->products_in_stock);
    }

    //guest não consegue remover produto
    public function test_guest_cannot_remove_stock()
    {
        $product = $this->createProductAndStock();

        $oldStockQuantity = $product->stock->products_in_stock; //100
        $payload = ['quantity' => 50];
        $response = $this->post('/baixar-produtos/1', $payload);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEquals($oldStockQuantity, $product->stock->products_in_stock);
    }

    //USER TESTS
    public function test_add_stock_page_is_displayed_correctly_to_the_user()
    {

        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = $this->createProductAndStock();
        $response = $this->actingAs($user)->get('/adicionar-produtos/1');
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    //user consegue adicionar estoque do produto
    public function test_user_can_add_stock()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = $this->createProductAndStock();
        $payload = ['quantity' => 200];
        $response = $this->actingAs($user)->post('/adicionar-produtos/1', $payload);
        $this->assertEquals($product->stock->products_in_stock, 300);
    }

    //página de remoção de produto funciona
    public function test_remove_stock_page_is_displayed_correctly_to_the_user()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = $this->createProductAndStock();
        $response = $this->actingAs($user)->get('/baixar-produtos/1');
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    //user consegue remover estoque do produto
    public function test_user_can_remove_stock()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = $this->createProductAndStock();

        $payload = ['quantity' => 100];
        $response = $this->actingAs($user)->post('/baixar-produtos/1', $payload);
        $this->assertEquals($product->stock->products_in_stock, 0);
    }

    //a tabela de reports salva dados sobre a adição de estoque
    public function test_function_save_reports_is_working_when_stock_is_added()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = $this->createProductAndStock();
        $payload = ['quantity' => 200];
        $response = $this->actingAs($user)->post('/adicionar-produtos/1', $payload);
        $table = DB::table('stock_movements')->where('product_id', $product->SKU)->first();
        $this->assertNotNull($table);
    }

    //a tabela de reporst salva dados sobre a remoção de estoque
    public function test_function_save_reports_is_working_when_stock_is_removed()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = $this->createProductAndStock();
        $payload = ['quantity' => 50];
        $response = $this->actingAs($user)->post('/baixar-produtos/1', $payload);
        $table = DB::table('stock_movements')->where('product_id', $product->SKU)->first();
        $this->assertNotNull($table);
    }

    //retorna erro quando o user tenta remover mais produtos do que existem no estoque
    public function test_validates_when_user_tries_to_remove_more_stock_than_it_can()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $product = $this->createProductAndStock();
        $payload = ['quantity' => 1000];
        $response = $this->actingAs($user)->post('/baixar-produtos/1', $payload);
        $response->assertStatus(302);
        $response->assertRedirect('/baixar-produtos/1');
    }

    /**
     * Helper function that creates an instance of Product, and creates a stock for it in the database
     */
    private function createProductAndStock(): Product
    {
        $product = Product::factory()->create();
        $product->stock()->create(['products_in_stock' => 100]);


        return $product;
    }
}
