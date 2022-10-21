<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;

class ProductController extends Controller
{
    //


    //Retourne l'ensemble  des produits de  la boutique

    public function getAllProduct(){
      $products=Product::all();
      return json_encode($products);
    }

    // Get Detail Product
    public function detailProduct($id){

      $product=Product::findOrFail($id);
      return json_encode($product);
    }

    // save products

    public function saveProduct(Request $request){
      $product_name=$request->input("product_name");
      $product_price=$request->input("product_price");
      $product_qte=$request->input("product_qte");
      $user_id=$request->input("user_id");

      Product::create([
        'product_name'=>$product_name,
        'product_price'=>$product_price,
        'product_qte'=>$product_qte,
        'user_id'=>$user_id,

      ]);
    }



}