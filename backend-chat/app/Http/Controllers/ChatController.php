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
                'sender' => $request->sender,
                'receiver' => $request->receiver,
                'members' => $request->members,
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

    public function getChat($id) {
        $chat = Chat::where('friend', $id)->get();

        return response()->json($chat);
    }

    public function getAllChats() {
        $chats = Chat::where('members', 'all', [\Auth::id()])->get();

        return response()->json($chats);
    }

    public function sendMessage(Request $request, $chatId) {
        $chat = Chat::where('_id', $chatId)->push('messages', $request->messages);

        return response()->json($chat);
    }
}
