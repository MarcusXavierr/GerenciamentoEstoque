<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($this->faker));

        $name  = $this->faker->productName;
        $sku   = Str::slug($name, '-'); //vou usar slug só nos testes
        $price = $this->faker->randomFloat(2, 1000, 20);
        $description = $this->faker->sentence(30);

        return [

            'SKU' => $sku,
            'name' => $name,
            'price' => $price,
            'description' => $description
        ];
    }
}
