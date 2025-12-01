<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'apiLogin','register','resetPassword']]);
    }

    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))){
            $request->session()->regenerate();
            if(auth()->user->role == 'sadmin'){
                return response()->json(['status'=>'true','user'=> auth()->user()],200);
            }
            return response()->json(['status' => true, 'user' => auth()->user()]);
        }

        return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()*60,
            'user' => JWTAuth::user()
        ]);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password) ?? bcrypt('123456'),
                'role' => 'user',
                'phone' => $request->phone ?? null,
                'email_verified_at' => now(),
            ]
        );
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'password' => bcrypt($request->password) ?? bcrypt('123456'),
                'role' => 'user',
                'phone' => $request->phone ?? null,
                'email_verified_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
            'user' => $user,
        ]);
    }
}
