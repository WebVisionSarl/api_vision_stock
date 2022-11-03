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


    public function update_product(Request $request){

        $product_id=$request->input("product_id");
        $new_name=$request->input("new_name");
        $new_price=$request->input("new_price");
        $new_qte=$request->input("new_qte");
        $img_prod=$request->input("img_prod");


        Product::whereId($product_id)->update([
            'product_qte'=>$new_qte,
            'product_price'=>$new_price,
            'product_name'=>$new_name,
        ]);

        $product=Product::findOrFail($product_id);
        return json_encode($product);

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
      $img_prod=$request->input("img_prod");
      $user_id=$request->input("user_id");

      Product::create([
        'product_name'=>$product_name,
        'product_price'=>$product_price,
        'img_prod'=>$img_prod,
        'product_qte'=>$product_qte,
        'user_id'=>$user_id,

      ]);
    }



}
