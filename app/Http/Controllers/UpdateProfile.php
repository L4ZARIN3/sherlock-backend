<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdateProfile extends Controller
{
    public function index(){
        return auth()->user();
    }

    public function update(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:8'
        ],[
            'email.required' => 'Email requerido.',
            'email.string' => 'Email invalido.',
            'email.email' => 'Email invalido.',
            'email.max' => 'Maximo de 255 caracteres para Email.',
            'email.unique' => 'Email ja está em uso.',

            'password.min' => 'Minimo de 8 caracteres para email.',
            'password.required_with' => 'Informe a confirmação de senha.',
            'password.same' => 'Confirmação de senha invalida.',

            'password_confirmation.required' => 'Informe a confirmação de senha.',
            'password_confirmation.min' => 'Minimo de 8 caracteres para confirmação de senha.'
        ]);

        $usuario = User::where('email', $request->email)->first();
        $id = auth()->user();

        if($usuario == true){
            if($id->id == $usuario->id){
                User::find($id->id)->update([
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
            }else{
                return response()->json(['error' => 'Email ja em uso'], 401);
            }
        }else{
            User::find($id->id)->update([
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
        }
        return response()->json(['message' => 'Informações alteradas com sucesso'], 200);


    }
}
