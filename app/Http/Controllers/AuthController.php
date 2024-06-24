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
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'phone' => 'required|string|regex:/^\+?[0-9]{10,15}$/|unique:users',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'city' => 'required|string|max:255',
            'college' => 'required|string',
        ], [
            'name.required' => 'The Name Field is Required.',
            'name.string' => 'The Name Must be a String.',
            'name.max' => 'The Name may not be Greater than 255 Characters.',
            'email.required' => 'The Email Field is Required.',
            'email.string' => 'The Email must be a String.',
            'email.email' => 'The Email must be a Valid Email Address.',
            'email.max' => 'The Email may not be Greater than 255 Characters.',
            'email.unique' => 'The Email is Already Exist.',
            'password.required' => 'The Password Field is Required.',
            'password.string' => 'The Password must be a String.',
            'password.min' => 'The Password must be At Least 6 Characters.',
            'password.confirmed' => 'The Password Confirmation does not Match.',
            'password_confirmation.required' => 'The Password Confirmation Field is Required.',
            'password_confirmation.string' => 'The Password Confirmation must be a String.',
            'password_confirmation.min' => 'The Password Confirmation Must be At Least 6 Characters.',
            'phone.required' => 'The Phone Field is Required.',
            'phone.string' => 'The Phone Number must be a String.',
            'phone.regex' => 'The phone Number Format is Invalid. It Must be between 10 to 15 Digits.',
            'phone.unique' => 'The Phone Number is Already Exist.',
            'dob.required' => 'Date of birth is Required.',
            'dob.date' => 'Date of birth format is Incorrect.',
            'dob.before' => 'Date of birth format is Invalid.',
            'gender.required' => 'Gender is Required.',
            'gender.in' => 'Gender must be either Male or Female.',
            'city.required' => 'City is Required.',
            'city.string' => 'City must be String.',
            'city.max' => 'The City may not be Greater than 255 Characters.',
            'college.required' => 'College Name is Required.',
            'college.string' => 'College Name must be String.'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => 'error',
                'error_messages' => $validator->errors()
            ]);
        }
        
     
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'dob' => $request->dob,
            'college' => $request->college,
            'gender' => $request->gender,
            'city' => $request->city,
            'address' => $request->address
        ]);
        
        $token = JWTAuth::fromUser($user);

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
