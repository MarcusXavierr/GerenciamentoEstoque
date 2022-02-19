<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PhpParser\Node\Expr\Print_;
use Tests\TestCase;

class StockMovementTest extends TestCase
{

    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private array $basePayload = [
        'id' => 1,
        'quantity' => 50
    ];
    //guest tenta adicionar
    public function test_guest_cannot_add_product_to_stock()
    {

        Product::factory()->create();
        $payload = [
            'id' => 1,
            'quantity' => 50
        ];
        $response = $this->post('/api/adicionar-produtos', $payload);

        $response->assertStatus(401);
        $response->assertSee('Not authenticated');
    }

    //guest tenta remover
    public function test_guest_cannot_remove_product_from_stock()
    {
        Product::factory()->create();
        $payload = [
            'id' => 1,
            'quantity' => 50
        ];

        $response = $this->post('/api/baixar-produtos', $payload);

        $response->assertStatus(401);
        $response->assertSee('Not authenticated');
    }

    //ADD //

    //adiciona com campos faltando
    public function test_user_tries_to_send_request_with_missing_fields_and_fails()
    {
        $payload = [
            'id' => 1,
        ];

        $response = $this->baseTestToAddStock($payload);
        $response->assertStatus(400);
        $response->assertSee('You did not fill in all the required fields.');
    }

    //adiciona um produto q nao existe
    public function test_user_tries_to_add_stock_to_a_product_that_does_not_exist_and_fails()
    {
        $response = $this->baseTestToAddStock(["id" => 10, 'quantity' => 50]);
        $response->assertStatus(404);
        $response->assertSee('product_id not found');
    }

    //adiciona valendo
    public function test_user_adds_stock_to_product_successfully()
    {
        $response = $this->baseTestToAddStock($this->basePayload);
        $response->assertStatus(201);
        // $product = Product::find(1);
        // $this->assertEquals($product->stock()->products_in_stock, 150);
    }

    //REMOVE //

    //remove com campos faltando
    public function test_user_makes_requests_with_missing_field_and_tries_to_remove_stock_and_fails()
    {
        $response = $this->baseTestToRemoveStock(['id' => 1]);

        $response->assertStatus(400);
        $response->assertSee('You did not fill in all the required fields.');
    }


    //remove um produto q nao existe
    public function test_user_tries_to_remove_stock_of_a_product_that_does_not_exist_and_fails()
    {
        $response = $this->baseTestToRemoveStock(['id' => 100, 'quantity' => 10]);
        $response->assertStatus(404);
        $response->assertSee('product_id not found');
    }
    //remove mais do que poderia
    public function test_user_tries_to_remove_more_products_than_are_in_stock_and_fails()
    {
        $response = $this->baseTestToRemoveStock(['id' => 1, 'quantity' => 500]);
        $response->assertStatus(401);
        $response->assertSee('you are trying to remove more products than there are');
    }
    //remove valendo
    public function test_user_successfully_removes_products_from_stock()
    {
        $response = $this->baseTestToRemoveStock(['id' => 1, 'quantity' => 50]);
        $response->assertStatus(201);
        $product = Product::find(1);
        $this->assertEquals($product->stock->products_in_stock, 50);
    }



    private function baseTestToAddStock(array $payload): TestResponse
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        Product::factory()->create()
            ->each(function ($product) {
                $product->stock()->create(['products_in_stock' => 100]);
            });


        $response = $this->post('/api/adicionar-produtos', $payload);
        return $response;
    }

    private function baseTestToRemoveStock(array $payload): TestResponse
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        Product::factory()->create()
            ->each(function ($product) {
                $product->stock()->create(['products_in_stock' => 100]);
            });


        $response = $this->post('/api/baixar-produtos', $payload);
        return $response;
    }
}
