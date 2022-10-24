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

      $value=2;

      $products=json_decode($products);

      // dd($products.size());

      for ($i=0;$i<$value;$i++) {

          // echo($products[$i])."<br/>";


      }

      Sale::create([
        "user_id"=>$user,
        "products"=>json_encode($products)
      ]);

    }


    public function  getAllSales(){
      Sale::all();
    }

}
