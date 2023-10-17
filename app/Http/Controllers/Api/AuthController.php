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
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        try {
            if (!Auth::attempt($credentials)) {
                return $this->error(null, 'Invalid credentials', 400);
            }

            /** @var User $user */
            $user = Auth::user();

            return $this->onSuccessfulLogin($user);
        } catch (\Throwable $th) {
            return $this->error(null, $th->getMessage(), 500);
        }
    }

    // Signup APUI Function
    public function register(SignupRequest $request)
    {
        $credentials = $request->validated();

        try {
            $credentials['password'] = bcrypt($request->password);

            /** @var User $user */
            $user = User::create($credentials);

            return $this->onSuccessfulLogin($user);
        } catch (\Throwable $th) {
            return $this->error(null, $th->getMessage(), 500);
        }
    }



    // Logout function
    public function logout(Request $req)
    {
        $user = $req->user();
        $user->currentAccessToken()->delete();

        return $this->success(null, 'User Successfully Logged Out', 204);
        // return response()->json([
        //     'status' => true,
        //     'message' => 'User Successfully Logged Out',
        // ], 204);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function onSuccessfulLogin(User $user)
    {
        $token = $user->createToken('API Token of ' . $user->name)->plainTextToken;

        return $this->success(compact('user', 'token'), 'Success');
    }
}
