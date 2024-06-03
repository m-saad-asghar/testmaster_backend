<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function logout(Request $request){
        // auth()->logout();
            return response()->json([
                "success" => 1,
                "message" => 'User is successfully logout'
            ]);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => 'error',
                "error_messages" => $validator->errors()
            ]);
        }

        if (!$token = JWTAuth::attempt($validator->validated())) {
            return response()->json([
                "success" => 'unauthorize'
            ]);
        }

        $user = User::where("email", $request->email)->first();

        if($user->active){
            return response()->json([
                "success" => 1,
                "jwt_token" => $this->createAuthToken($token)
            ]);
        }else{
            return response()->json([
                "success" => 'disable'
            ]);
        }

    }


    public function createAuthToken($token){
        return response()->json([
            "access_token" => $token,
            "token_type" => 'bearer',
            "expires_in" => JWTAuth::factory()->getTTL()*60,
            "user" => Auth::user()
        ]);
    }
}
