<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Sale;
use App\Models\ProductSale;

class SaleController extends Controller
{
    //

    public function getAllSaleToday(){

      // $today=;
      // $rapp=Sale::where('created_at',$today);


    }

    public function saveSale(Request $request){
      $user=$request->input("user");
      $products=$request->input("products");

      $products=[{
      	"product_name": "Mais"
      }];

      foreach ($products as $key => $value) {

          echo $value;

        // ProductSale::create([
        //   "product_name"=>$value->product_name,
        //   "product_qte"=>$value->product_qte,
        //   "user_id"=>$user,
        // ]);

      }

      Sale::create([
        "user_id"=>$user,
        "products"=>$products
      ]);

    }

}
