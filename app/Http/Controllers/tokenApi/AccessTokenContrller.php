<?php

namespace App\Http\Controllers\tokenApi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokenContrller extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
            'device_name' => 'string|max:255'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $device_name = $request->post('device_name', $request->userAgent());
            $token = $user->createToken($device_name);

            return response()->json([
                'token' => $token->plainTextToken,
                'message' => 'token created successfully',
                'suer' => $user
            ]);
        };

        return response()->json([
            'code' => 0,
            'message' => 'invalid credentials'
        ]);
    }

    public function destroy($token = null)
    {
        $user = Auth::guard('sanctum')->user();
        // dd($user)

        if ($token == null) {
            $user->tokens()->delete();
        } else {

            $currentToken = PersonalAccessToken::findToken($token);
            if ($user && $user->id == $currentToken->tokenable_id) {
                $currentToken->delete();
            }
        }
        return  response()->json('token deleted')->setStatusCode(204);
    }
}
