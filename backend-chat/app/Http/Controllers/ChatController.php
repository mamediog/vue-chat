<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;

class ChatController extends Controller
{

    /**
     * Faz o cadastro de um novo chat
     */
    public function create(Request $request)
    {
        try {
            $this->validate($request, [
                'messages' => 'required',
            ]);

            $data = [
                'users' => $request->users,
                'messages' => $request->messages,
            ];

            $chat = Chat::create($data);

            $chat->save();

            return response()->json(['status' => 'success', 'chat' => $chat]);
        } catch (\Exception $e) {
            $errors = \array_values($e->errors());
            return response()->json(['status' => 'fail', 'error' => $errors[0][0] ], 400);
        }
    }
}
