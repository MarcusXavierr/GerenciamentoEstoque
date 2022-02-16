<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['SKU', 'name', 'price', 'description'];


    public function stock()
    {
        return $this->hasOne(Stock::class, 'product_id', 'SKU');
    }

}
