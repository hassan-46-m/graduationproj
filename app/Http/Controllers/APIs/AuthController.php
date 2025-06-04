<?php

namespace App\Http\Controllers\APIs;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed"
        ]);

       $user = User::create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => bcrypt($data['password']),
        ]);





        $token = $user->createToken("user_token")->plainTextToken;


        $reponse = [
            "status" => 200,
            "data" => $user,
            "token" => $token,
            "message" => "Create User successfully"
        ];


        return response($reponse, 200);
    }


    public function login(Request $request)
    {
        $data = $request->validate([

            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where("email", $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                "status" => 401,
                "message" => "Invalid email or password"
                ], 401);
                }
                $token = $user->createToken("user_token")->plainTextToken;
                $user->save();
                $reponse = [
                    "status" => 200,
                    "data" => $user,
                    "token" => $token,
                    "message" => "Login successfully"
                    ];
                    return response($reponse, 200);

    }
    

    public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json([
        "status" => 200,
        "message" => "Logged out successfully"
    ], 200);
}

}
