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
use Illuminate\Support\Facades\Log;


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
      $total=$request->input("total");


      $sales=Sale::create([
        "user_id"=>$user,
        "clientname"=>$clientname,
        "contactclient"=>$contactclient,
        "paymethod"=>$paymethod,
        "totalpay"=>$total,
      ]);

      for ($i=0;$i<$size;$i++) {


        ProductSale::create([
          'sale_id'=>$sales->id,
          'product_name'=>$products[$i]["name"],
          'product_qte'=>$products[$i]["qte"],
          'product_price'=>$products[$i]["unit_price"],
          'user_id'=>$user,
        ]);


        $getToUp=Product::where('product_name',$products[$i]["name"])->first();
        Product::whereId($getToUp->id)->update([
            //soustract on BD
            'product_qte'=>$getToUp->product_qte-$products[$i]["qte"],
        ]);
      }

    }


    public function  getAllSales(){
      return json_encode(Sale::all());
    }

}
