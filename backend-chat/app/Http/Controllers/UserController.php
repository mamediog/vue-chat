<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function create(Request $request)
    {
        try {
            $emailValidate = $request->has('token') ? ['email' => 'required|email'] : ['email' => 'required|email|unique:users'];
            $this->validate($request, [
                'password' => 'required|min:6',
                'name' => 'required',
                'phone' => 'required'
            ] + $emailValidate);

            if ($request->has('token')) {
                $user->fill($request->except('password', 'confirm') + [
                    'password' => password_hash($request->password, PASSWORD_DEFAULT)
                ]);
            } else {
                $user = User::create($request->except('password', 'confirm') + [
                    'password' => password_hash($request->password, PASSWORD_DEFAULT)
                ]);
            }

            $apikey = base64_encode(str_random(40));
            $user->api_key = $apikey;
            $user->save();

            return response()->json(['status' => 'success', 'user' => $user, 'token' => $apikey]);
        } catch (\Exception $e) {
            $errors = \array_values($e->errors());
            return response()->json(['status' => 'fail', 'error' => $errors[0][0] ], 400);
        }
    }

    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function hasUser(Request $request)
    {
        $user = User::where('email',$request->email)->first();

        if ($user !== null) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'failed']);
    }
}
