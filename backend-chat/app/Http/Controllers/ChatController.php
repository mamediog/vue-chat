<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;

class ChatController extends Controller
{

    /**
     * Create a new Chat
     *
     * @param Request $request
     * @return void
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

    /**
     * Get Chat by a friend "id"
     *
     * @param [type] $id
     * @return void
     */
    public function getChat($id) {
        $chat = Chat::where('friend', $id)->get();

        return response()->json($chat);
    }

    /**
     * Get all chats from logged user
     *
     * @return void
     */
    public function getAllChats() {
        $chats = Chat::where('members', 'all', [\Auth::id()])->get();

        return response()->json($chats);
    }

    /**
     * Send a message to the current chat
     *
     * @param Request $request
     * @param [type] $chatId
     * @return void
     */
    public function sendMessage(Request $request, $chatId) {
        $chat = Chat::where('_id', $chatId)->push('messages', $request->messages);

        return response()->json($chat);
    }
}
