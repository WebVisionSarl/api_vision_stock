<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Credit;
use App\Models\ProductSale;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    //

    public function getAllSaleToday(){

      // $today=;
      // $rapp=Sale::where('created_at',$today);


    }

    public function paginateSales($limit){

      
      $data = Sale::orderBy('id','DESC')->simplePaginate($limit);

      return json_encode($data);

    }

    public function credits(){
      $credits=Sale::where("paymethod","Crédit")->limit(15)->get();

      return json_encode($credits);
    }

    public function soldecredit(Request $request){

      $sale_id=$request->input("sale_id");
      $paysolde=$request->input("paysolde");
      $credit_total=$request->input("credit_total");
      $get_cred=Credit::where("sale_id",$sale_id)->first();

      // dd($);

      if($get_cred){

          $reste_a_payer=$get_cred->total_credit-$paysolde-$get_cred->payer;
          if($get_cred->payer>=$get_cred->total_credit)
            $reste_a_payer=0;

            $newpay=Credit::whereId($get_cred->id)->update([
                'payer'=>$get_cred->payer+$paysolde,
                'reste_a_payer'=>$reste_a_payer,
              'total_credit'=>$get_cred->total_credit
          ]);


            $newcount=Credit::where("sale_id",$sale_id)->first();

            if($reste_a_payer>0){

              Sale::whereId($sale_id)->update([
                  'totalpay_credit'=>$newcount->total_credit-$newcount->payer,
              ]);

                return json_encode($reste_a_payer);
            }else{
              Sale::whereId($sale_id)->update([
                  'paymethod'=>"Espèce",
              ]);

              return json_encode($reste_a_payer);
            }

      }else{

        $total_cred=Sale::where('id',$sale_id)->first()->totalpay;

        Credit::create([
            'sale_id'=>$sale_id,
            'payer'=>$paysolde,
            'reste_a_payer'=>$total_cred-$paysolde,
            'total_credit'=>$total_cred
        ]);

        if($paysolde>=$total_cred){
          Sale::whereId($sale_id)->update([
              'paymethod'=>"Credit Payer",
              'totalpay_credit'=>$total_cred-$paysolde,
          ]);
        }else{
          Sale::whereId($sale_id)->update([
              'totalpay_credit'=>$total_cred-$paysolde,
          ]);

        }

          return json_encode($total_cred-$paysolde);

      }

    }

    public function saveExpense(Request $request){
      $expense=new Expense();
      $expense->motif=$request->input("motif");
      $expense->price=$request->input("price");
      $expense->save();
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


    // Stat five month past

    public function statfive(){

      $startDate = Carbon::now()->subMonths(4)->startOfMonth(); // Date de début pour les 5 derniers mois
      $endDate = Carbon::now()->endOfMonth(); // Date de fin pour les 5 derniers mois

      $stat=[];

    
      $sale=Sale::whereBetween('created_at',[Carbon::now()->subMonths(4)->startOfMonth(), Carbon::now()->subMonths(4)->endOfMonth()])->get()->sum("totalpay");
      
      array_push($stat,$sale);
    
    
      $sale=Sale::whereBetween('created_at',[Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->subMonths(3)->endOfMonth()])->get()->sum("totalpay");
      
      array_push($stat,$sale);
    
    
      $sale=Sale::whereBetween('created_at',[Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()])->get()->sum("totalpay");
      
      array_push($stat,$sale);
    
    
      $sale=Sale::whereBetween('created_at',[Carbon::now()->subMonths(1)->startOfMonth(), Carbon::now()->subMonths(1)->endOfMonth()])->get()->sum("totalpay");
      
      array_push($stat,$sale);
    
    
      $sale=Sale::whereBetween('created_at',[Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get()->sum("totalpay");
      
      array_push($stat,$sale);
    
      

      return json_encode($stat);

    }
    


    public function  resumeToday(){

      $mostOrderedProducts = DB::table('product_sales')->whereDate('created_at',Carbon::today())
    ->select('product_name', DB::raw('SUM(product_qte) as total_quantity'))
    ->groupBy('product_name')
    ->orderByDesc('total_quantity')
    ->take(5)
    ->get();

      $sales=Sale::whereDate('created_at',Carbon::today())->get();
      $creances=Credit::whereDate('created_at',Carbon::today())->get();
      $count_expenses=Expense::whereDate('created_at',Carbon::today())->get()->sum("price");
      $count_sales=$sales->sum('totalpay');
      $count_creances=$creances->sum('total_credit');

      $structure=Setting::first()->structure;


      $statSales=[];
      $statCredit=[];
      $statExpenses=[];

    
      $sale=Sale::whereBetween('created_at',[Carbon::now()->subMonths(4)->startOfMonth(), Carbon::now()->subMonths(4)->endOfMonth()])->get()->sum("totalpay");
      $credit=Credit::whereBetween('created_at',[Carbon::now()->subMonths(4)->startOfMonth(), Carbon::now()->subMonths(4)->endOfMonth()])->get()->sum("total_credit");
      $expenses=Expense::whereBetween('created_at',[Carbon::now()->subMonths(4)->startOfMonth(), Carbon::now()->subMonths(4)->endOfMonth()])->get()->sum("price");
      
      array_push($statSales,$sale);
      array_push($statCredit,$credit);
      array_push($statExpenses,$expenses);
    
    
      $sale=Sale::whereBetween('created_at',[Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->subMonths(3)->endOfMonth()])->get()->sum("totalpay");
      $credit=Credit::whereBetween('created_at',[Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->subMonths(3)->endOfMonth()])->get()->sum("total_credit");
      $expenses=Expense::whereBetween('created_at',[Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->subMonths(3)->endOfMonth()])->get()->sum("price");
      
      array_push($statSales,$sale);
      array_push($statCredit,$credit);
      array_push($statExpenses,$expenses);
    
      $sale=Sale::whereBetween('created_at',[Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()])->get()->sum("totalpay");
      $credit=Credit::whereBetween('created_at',[Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()])->get()->sum("total_credit");
      $expenses=Expense::whereBetween('created_at',[Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()])->get()->sum("totalpay");
      
      array_push($statSales,$sale);
      array_push($statCredit,$credit);
      array_push($statExpenses,$expenses);

      $sale=Sale::whereBetween('created_at',[Carbon::now()->subMonths(1)->startOfMonth(), Carbon::now()->subMonths(1)->endOfMonth()])->get()->sum("totalpay");
      $credit=Credit::whereBetween('created_at',[Carbon::now()->subMonths(1)->startOfMonth(), Carbon::now()->subMonths(1)->endOfMonth()])->get()->sum("total_credit");
      $expenses=Expense::whereBetween('created_at',[Carbon::now()->subMonths(1)->startOfMonth(), Carbon::now()->subMonths(1)->endOfMonth()])->get()->sum("price");
      
      array_push($statSales,$sale);
      array_push($statCredit,$credit);
      array_push($statExpenses,$expenses);
    
      $sale=Sale::whereBetween('created_at',[Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get()->sum("totalpay");
      $credit=Credit::whereBetween('created_at',[Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get()->sum("total_credit");
      $expenses=Expense::whereBetween('created_at',[Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get()->sum("price");
      
      $statMonth=[];
      array_push($statSales,$sale);
      array_push($statCredit,$credit);
      array_push($statExpenses,$expenses);
      array_push($statMonth,$sale,$credit,$expenses);


      return json_encode(['sales'=>$sales,'count_sales'=>$count_sales,'count_creances'=>$count_creances,'count_expenses'=>$count_expenses,'most_order'=>$mostOrderedProducts,'structure'=>$structure,'stat_sales'=>$statSales,'stat_cred'=>$statCredit,'stat_exp'=>$statExpenses,'thismonth'=>$statMonth]);
    }
    


    public function  getAllExpenses(Request $request){
      $expenses=Expense::limit(10)->orderBy('id','DESC')->get();
      $count=$expenses->sum('price');

      return json_encode(['expenses'=>$expenses,'count_expenses'=>$count]);
    }


}
