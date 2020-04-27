<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        // try {
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
        // } catch (\Exception $e) {
        //     $errors = \array_values($e->errors());
        //     return response()->json(['status' => 'fail', 'error' => $errors[0][0] ], 400);
        // }
    }

    public function hasUser(Request $request)
    {
        $user = User::where('email',$request->email)->first();

        if ($user !== null) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'failed']);
    }

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'name' => 'required',
                'company' => 'required',
                'type' => 'required',
                'phone' => 'required'
            ]);

            $client = \Auth::user();

            if ($request->has('password')) {
                $client->fill($request->except('password', 'confirm') + [
                    'password' => password_hash($request->password, PASSWORD_DEFAULT)
                ]);
            } else {
                $client->fill($request->all());
            }

            $apikey = base64_encode(str_random(40));
            $client->api_key = $apikey;
            $client->save();

            return response()->json(['status' => 'success', 'user' => $client, 'token' => $apikey]);
        } catch (\Exception $e) {
            $errors = \array_values($e->errors());
            return response()->json(['status' => 'fail', 'error' => $errors[0][0] ], 400);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $client = Client::where('email', $request->email)->first();
        if (!$client) {
            return response()->json(['status' => 'notexists'], 400);
        }

        if (password_verify($request->password, $client->password)) {
            $apikey = base64_encode(str_random(40));
            Client::where('email', $request->input('email'))->update(['api_key' => $apikey]);

            return response()->json(['status' => 'success', 'token' => $apikey, 'user' => $client]);
        }

        return response()->json(['status' => 'fail'], 400);
    }

    public function verify(Request $request)
    {
        $client = Client::where('api_key', $request->header('Authorization'))->first();

        if ($client) {
            return response()->json(['status' => 'success', 'user' => $client]);
        }

        return response()->json(['status' => 'fail'], 404);
    }

    public function getEmailByToken($token, Request $request)
    {
        $client = Client::where('password_creator', $token)->first();

        if ($client) {
            return response()->json(['status' => 'success', 'email' => $client->email]);
        }

        return response()->json(['status' => 'fail'], 404);
    }
}
