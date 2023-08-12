<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatController extends Controller
{

    public function create(Request $request) {
        $senderId = auth()->user()->id;
        $recipientId = $request->input('recipient_id');

        $chat = Chat::create([
            'user_id_1' => $senderId,
            'user_id_2' => $recipientId
        ]);

        return response()->json(['chatId' => $chat->id]);
    }

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
            // $chat = Chat::create([
            //     'user_id_1' => $authUserId,
            //     'user_id_2' => $recipientId,
            // ]);

            $chatId = null;
        } else {
            $chatId = $chat->id;
        }

        return response()->json(['chatId' => $chatId]);
    }

    public function getChatData($id) : JsonResponse {
        // should return sender name, recipient name for now
        $chat = Chat::findorFail($id);
        $userId = auth()->user()->id;

        $lastMessage = $chat->messages->last() ?? "No messages yet!";

        if($chat->user_id_1===$userId) {
            $sender = auth()->user();
            $recipient = User::findorFail($chat->user_id_2);
        } else if ($chat->user_id_2===$userId){
            $sender = auth()->user();
            $recipient = User::findorFail($chat->user_id_1);
        }

        $responseArray = [
            'sender' => $sender,
            'recipient' => $recipient,
            'lastMessage' => $lastMessage
        ];

        return response()->json($responseArray);
    }

}