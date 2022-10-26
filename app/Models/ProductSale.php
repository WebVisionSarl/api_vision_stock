<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_name',
        'product_qte',
        'product_price',
        'user_id',
    ];

}
