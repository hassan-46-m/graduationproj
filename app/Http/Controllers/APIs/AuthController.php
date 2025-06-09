<?php

namespace App\Http\Controllers\APIs;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent successfully.'])
            : response()->json(['message' => 'Unable to send reset link.'], 400);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully.'])
            : response()->json(['message' => 'Invalid token or email.'], 400);
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
