<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        User::where('email', $request->input('email'))->update(['api_key' => $token]);

        return $this->respondWithToken($token);
    }

    /**
     * JWT invalida o token do usuário logado
     */
    public function logout () {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 999
        ], 200);
    }

    /**
     * Verify if the user are logged
     */
    public function isLogged(Request $request) 
    {
        $user = User::where('api_key', explode(' ', $request->header('Authorization'))[1])->first();
        if ($user) {
            return response()->json(['status' => 'success', 'user' => $user]);
        }

        return response()->json(['status' => 'Unauthorized'], 401);
    }
}
