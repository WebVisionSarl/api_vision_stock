<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;


        protected $fillable = [
            'sale_id',
            'reste_a_payer',
            'payer',
            'total_credit',
        ];

}
