<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 1,
            'message' =>  'Registro Exitoso'
        ]);
        
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email','=', $request->email)->first();

        if(isset($user->id)){

            if(Hash::check($request->password, $user->password))
            {   
                $token = $user->createToken("auth_token")->plainTextToken;

                return response()->json([
                    'status' => 1,
                    'message' => 'Usuario Logeado',
                    'access_token' => $token
                ]);
            }else{
                return response()->json([
                    'status' => 0,
                    'message' => 'Credenciales incorrectas'
                ],404);
            }

        }else{
            return response()->json([
                'status' => 0,
                'message' => 'Usuario no registrado'
            ],404);
        }

    }
}
