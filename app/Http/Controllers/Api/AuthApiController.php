<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function register(Request $request) {
        
        $request->validate([
            "name" => "required|min:3",
            "email" => "required|email|unique:users",
            "password" => "required|min:8|confirmed",
            // "password_confirmation" => "same:password",
        ]);

        // return $request;

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        // if (Auth::attempt($request->only(["email","password"]))) {
        //     $token = Auth::user()->createToken("phone")->plainTextToken;
        //     return response()->json($token);
        // }

        // return response()->json(["message"=>"User is Unauthorized.."], 401);

        return response()->json([
            "message" => "User is created.",
            "success" => true,
        ],200);
    }

    public function login(Request $request) {
        
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:8",
        ]);

        // return $request;

        if (Auth::attempt($request->only(["email","password"]))) {
            $token = Auth::user()->createToken("phone")->plainTextToken;
            return response()->json([
                "message" => "Login Successful.",
                "success" => true,
                "token" => $token,
                "auth" => new UserResource(Auth::user())
            ]);
        }
        // return response()->json(["message"=>"User is Unauthorized.."], 401);
        return response()->json([
            "message" => "User Not Found.",
            "success" => false,
        ],401);
    }

    public function logout() {
        Auth::user()->currentAccessToken()->delete();
        // return response()->json(["message"=>"Logout Successfully."], 204);
        return response()->json([
            "message" => "Logout Successfully.",
            "success" => true,
        ],200);
    }

    public function logoutAll() {
        Auth::user()->tokens()->delete();
        return response()->json(["message"=>"Logout Successfully."], 204);
    }

    public function tokens() {
        return Auth::user()->tokens;
    }
}
