<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Credit;
use App\Models\ProductSale;
use Illuminate\Support\Facades\Log;


class SaleController extends Controller
{
    //

    public function getAllSaleToday(){

      // $today=;
      // $rapp=Sale::where('created_at',$today);


    }

    public function credits(){
      $credits=Sale::where("paymethod","Crédit")->get();

      return json_encode($credits);
    }

    public function soldecredit(Request $request){

      $sale_id=$request->input("sale_id");
      $paysolde=$request->input("paysolde");
      $credit_total=$request->input("credit_total");
      $get_cred=Credit::where("sale_id",$sale_id)->first();

      // dd($);

      if($get_cred){

          $reste_a_payer=$get_cred->total_credit-$paysolde;
          if($get_cred->payer>=$get_cred->total_credit)
            $reste_a_payer=0;

            $newpay=Credit::whereId($get_cred->id)->update([
                'payer'=>$get_cred->payer+$paysolde,
                'reste_a_payer'=>$reste_a_payer,
                'total_credit'=>$get_cred->total_credit
            ]);

            if($reste_a_payer>0){
                return json_encode("remboursement");
            }else{
              Sale::whereId($sale_id)->update([
                  'paymethod'=>"Espèce",
              ]);

              return json_encode("remboursement_total");
            }

      }else{

        Credit::create([
            'sale_id'=>$sale_id,
            'payer'=>$paysolde,
            'reste_a_payer'=>$credit_total-$paysolde,
            'total_credit'=>$credit_total
        ]);

        if($paysolde>=$credit_total){
          Sale::whereId($sale_id)->update([
              'paymethod'=>"Espèce",
          ]);
        }

          return json_encode("nouveaupaiement");

      }

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
