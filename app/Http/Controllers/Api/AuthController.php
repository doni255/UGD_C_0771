<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facedes\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facedes\Session;


class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();//Mengambil seluruh data input dan menyimpan dalam variabel registrationData

        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
         
            
        ]); //rule validasi input saat register

        if($validate->fails()) //Check are the input is match with the rule validation
            return response(['message' => $validate->errors()], 400); //Return validation error input

            // $uploadFolder = 'users';
            // $image = $request->file('image');
         

        $registrationData['password'] = bcrypt($request -> password); //for encrpyt the password

        $user = User::create($registrationData); //Create new user

        return response([
            'message' => 'Register Succes',
            'user' => $user
        ], 200);//return user data in json
    }

    public function login(Request $request){
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if(!Auth::attempt($loginData))
        return response(['message' => 'Invalid Credentials'], 401); //mengembalikan error gagal login

        $user = Auth::user();
        $token = $user ->createToken('Authentication Token')->accessToken; //generate token

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token          
        ]); //return data user dan token dalam bentuk json
    }

    // public function logoutApi (Request $request){
    //     $request->user()->token()->revoke();
    //     return response()->json([
    //     'message' => 'Successfully logged out'
    //     ]);
    // }
}
