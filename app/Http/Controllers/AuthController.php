<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Indicamos que haremos uso de los modelo usuarios
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Primer métodp para crear usuarios
    public function create(Request $request)
    {
        //Creamos las reglas con las que aceptaremos los compos
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
        ];
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            //Encriptamos la contraseña
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            //Le pasamos un token al cual lo declarmos como API TOKEN
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }
    //Funcion para logearse
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string',
        ];
        $validator = \Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }
        //Si lo ingresado no coincide con registros en las bd retornamos un error de autentificación
        if(!Auth::attempt($request->only('email','password'))) {
            return response()->json([
                'status' => false,
                'errors' => ['Unauthorizad']
                //Error 401 el cual significa no autorizado
            ], 401);
        }
        //Si todo marcha bien que coincida con el correo 
        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            //Mandamos en data la informacion del usuario
            'data' => $user,
            //Le pasamos un token al cual lo declarmos como API TOKEN
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }
    //Funcion para cerrar sesión
    public function logout(Request $request){
        //Eliminamos todos los token que haya generado el usuario
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ], 200);
    }
}
