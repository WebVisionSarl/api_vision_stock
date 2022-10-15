<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //

    public function registerUser(Request $request){


        $name = $request->input('name');
        $email= $request->input('email');
        $phone= $request->input('phone');
        $password= $request->input('password');

        $validated = $request->validate([
            'phone' => 'required|unique:users|max:255',
        ]);

        // if(!$validated)
        //     http_response_code(401); 

            $register=User::create([
                'name'=>$name,
                'phone'=>$phone,
                'email'=>$email,
                'password'=>Hash::make($password),
            ]);

            return json_encode('ok');
    }




    public function login(Request $request){

        $phone= $request->input('phone');
        $mdp= $request->input('password');
        $verif=User::where('phone',$phone)->first();

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




}
