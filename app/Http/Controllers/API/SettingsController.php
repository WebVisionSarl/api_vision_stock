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

  public function configTheme($name){

    $setting_id=Setting::first()->id;
     Setting::whereId($setting_id)->update([
         'theme'=>$name,
     ]);

  }

}
