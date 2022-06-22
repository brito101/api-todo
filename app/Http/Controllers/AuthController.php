<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $array = ['error' => ''];
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $user = new User();
        $user->name = $email;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        return $array;
    }

    public function auth(Request $request)
    {
        $array = ['error' => ''];

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $text = time() . rand(0, 9999);
            $token = $user->createToken($text);
            $array['token'] = $token->plainTextToken;
        } else {
            $array['error'] = 'Credenciais inválidas';
        }

        return $array;
    }

    public function logout(Request $request)
    {
        $array = ['error' => ''];

        $user = $request->user();

        $user->tokens()->delete();

        return $array;
    }
}
