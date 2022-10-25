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
      $clientname=$request->input("clientname");
      $contactclient=$request->input("contactclient");
      $paymethod=$request->input("paymethod");
      $products=$request->input("products");
      $size=$request->input("size_prod");
      $products=json_decode($products);


      Sale::create([
        "user_id"=>$user,
        "clientname"=>$clientname,
        "contactclient"=>$contactclient,
        "paymethod"=>$paymethod,
      ]);

      for ($i=0;$i<size;$i++) {
        ProductSale::create([
          'product_name'=>$products[$i]->product_name,
          'product_qte'=>$products[$i]->product_name,
          'product_price'=>$products[$i]->product_,
          'user_id'=>$user,
        ]);
      }

    }


    public function  getAllSales(){
      Sale::all();
    }

}
