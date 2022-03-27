<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request){
        
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid login details'], 401);
    }
    
    $user = User::where('email', $request['email'])->firstOrFail();
    
    $token = $user->createToken('auth_token')->plainTextToken;
    
    return response()->json([
               'access_token' => $token,
               'token_type' => 'Bearer',
    ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "Success unauthenticated."], 200);
        
    }
}
