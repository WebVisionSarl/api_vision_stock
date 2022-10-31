<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    //

    public function registerUser(Request $request){


        $name = $request->input('name');
        $phone= $request->input('phone');
        $password= $request->input('password');
        $role= $request->input('role_id');

        $validated = $request->validate([
            'phone' => 'required|unique:users|max:255',
        ]);

            $register=User::create([
                'name'=>$name,
                'phone'=>$phone,
                'role_id'=>$role,
                'password'=>Hash::make($password),
            ]);

            return json_encode('ok');
    }




    public function login(Request $request){

        $phone= $request->input('phone');
        $mdp= $request->input('password');
        $verif=User::with("role")->where('phone',$phone)->first();

        if($verif){

            if(Hash::check($mdp, $verif->password)){
                 return json_encode(['status'=>'ok','data'=>$verif]);
             }
             else{
                return json_encode(['status'=>'ko']);
             }

        }else
            return json_encode(['status'=>'ko']);

    }


    public function getProfil(Request $request){

      $user_phone=$request->input("phone");
      $user=User::where("phone",$user_phone)->first();

      return json_encode($user);

    }


    public function createNewRole(Request $request){

      $role=$request->input("role");

      Role::create(['libelle'=>$role]);
    }

    public function createNewUser(Request  $request){

        $role=$request->input("role");
        $username=$request->input("username");
        $phone=$request->input("phone");
        $password=$request->input("password");

        User::create([
          "role_id"=>$role,
          "name"=>$username,
          "phone"=>$phone,
          "password"=>Hash::make($password)
        ]);

    }

    // public function


}
