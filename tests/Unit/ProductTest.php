<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class ProductTest extends TestCase
{
    public function test_string_to_float_conversion_function_works()
    {
        $productController = new ProductController();
        //get private attribute $treatString
        $reflectionClass = new ReflectionClass(ProductController::class);
        $reflectionProperty = $reflectionClass->getProperty('treatString');
        $reflectionProperty->setAccessible(true);
        $treatString = $reflectionProperty->getValue($productController);

        $result = $treatString->convertStringToFloat('1.000,50');
        $this->assertEquals($result, 1000.50);
    }
}
