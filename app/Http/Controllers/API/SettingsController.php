<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Setting;

class SettingsController extends Controller
{

  public function configTheme(){

    $setting=Setting::first();

    if($setting->theme=="theme-dark"){
         Setting::whereId($setting->id)->update([
             'theme'=>"theme-white",
         ]);

       return json_encode("theme-white");

    }else{
         Setting::whereId($setting->id)->update([
             'theme'=>"theme-dark",
         ]);

        return json_encode("theme-dark");
    }

  }

  public function getsettings(){
    return json_encode(Setting::first());
  }

}
