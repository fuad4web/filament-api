<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponse;

    // Login API Function
    public function login(LoginRequest $request) {
        try {
            $credentials = $request->validated();
            
            if($credentials->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $credentials->errors()
                ], 401);
            }

            if(!Auth::attempt($credentials)) {
                return $this->error('', 'Invalid Credentials', 401);
            }

            $user = User::where('email', $request->email)->first();
            return $this->success([
                'user' => $user,
                'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken,
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $th->getMessage()
            ], 500);
        }
    }
    

    // Signup APUI Function
    public function register(SignupRequest $request) {
        try {
            $credentials = $request->validated();

            if($credentials->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $credentials->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            return $this->success([
                'user' => $user,
                'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken,
            ]);

        }  catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $th->getMessage()
            ], 500);
        }
    }
    


    // Logout function
    public function logout(Request $req) {
        $user = $req->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Successfully Logged Out'
        ], 204);
    }
}
