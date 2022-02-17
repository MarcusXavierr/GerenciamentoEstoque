<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //Crate an admin account
        User::factory()->create(
            [
                'email'    => 'admin@email.com',
                'password' =>  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'name' => 'Admin'
            ]
        );

        //Create 10 products, and create and stock table for each product
        Product::factory(10)->create()
            ->each(function ($product) {
                $this->addStock($product);
            });
    }

    private function addStock($product)
    {
        $product->stock()->create(['products_in_stock' => 100]);
    }
}
