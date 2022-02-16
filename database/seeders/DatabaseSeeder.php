<?php

namespace Database\Seeders;

use App\Models\Product;
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
        // \App\Models\User::factory(10)->create();

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
