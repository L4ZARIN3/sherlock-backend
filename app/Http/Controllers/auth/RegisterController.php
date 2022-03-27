<?php

namespace App\Http\Controllers\auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request){

        $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
		        'password_confirmation' => 'min:8'
            ],[
                'name.required' => 'Usuario requerido.',
                'name.string' => 'Usuario invalido.',
                'name.max' => 'Maximo de 255 caracteres.',
                'email.required' => 'Informe um email.',
                'email.string' => 'Email invalido.',
                'email.email' => 'Email invalido.',
                'email.max' => 'Maximo de 255 caracteres.',
                'email.unique' => 'Email ja em uso.',
                ]);
            
        $user = User::create([
                            'name' => $request['name'],
                            'email' => $request['email'],
                            'password' => Hash::make($request['password']),
                ]);
            
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                          'access_token' => $token,
                               'token_type' => 'Bearer',
            ]);

    }
}
