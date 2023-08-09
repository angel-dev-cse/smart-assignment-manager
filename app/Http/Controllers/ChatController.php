<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatController extends Controller
{

    public function show($id)
    {
        $messages = Message::where('chat_id', $id)->with('user')->orderBy('created_at')->get();

        return response()->json(['messages' => $messages]);
    }
    //
    public function sendMessage(Chat $chat, Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
                'content' => 'string|required',
                'file' => 'file|nullable'
            ]
        );

        $message = $chat->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'file_path' => $validated['file'] ? $validated['file']->store('chat-attachments', 'public') : null,
        ]);
        
        event(new SendMessage($message));

        return response()->json(['message' => 'Message sent successfully!']);
    }

    // ChatController.php

    public function getChatId($recipientId)
    {
        $authUserId = auth()->id();

        // Check if a chat already exists between the authenticated user and the recipient
        $chat = Chat::where(function ($query) use ($authUserId, $recipientId) {
            $query->where('user_id_1', $authUserId)
                ->where('user_id_2', $recipientId);
        })->orWhere(function ($query) use ($authUserId, $recipientId) {
            $query->where('user_id_2', $authUserId)
                ->where('user_id_1', $recipientId);
        })->first();

        // If a chat doesn't exist, create a new one
        if (!$chat) {
            $chat = Chat::create([
                'user_id_1' => $authUserId,
                'user_id_2' => $recipientId,
            ]);
        }

        return response()->json(['chatId' => $chat->id]);
    }

}