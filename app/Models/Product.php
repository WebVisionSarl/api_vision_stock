<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


        protected $fillable = [
            'product_name',
            'product_qte',
            'img_prod',
            'product_price',
            'product_unit',
            'user_id',
        ];

}
