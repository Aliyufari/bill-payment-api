<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $credentials = $request->validated();

        DB::beginTransaction();

        try {
            $credentials['password'] = bcrypt($credentials['password']);
            $user = User::create($credentials);
            
            // Create wallet for the user
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);

            DB::commit();

            $token = Auth::guard('api')->login($user);

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Error registering user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        try {
            $token = Auth::guard('api')->attempt($credentials);

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password.',
                ], 401);
            }

            $user =  Auth::guard('api')->user();

            return response()->json([
                'status' => true,
                'message' => 'User logged in successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error loggin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('api')->logout();
    
            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error logging out: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh();
            return $this->respondWithToken($newToken);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error refreshing token: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
