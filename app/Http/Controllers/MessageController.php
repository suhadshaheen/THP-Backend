<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{


   public function index(Request $request)
    {
        $senderId = $request->query('sender_id');
        $receiverId = $request->query('receiver_id');
        $userId = Auth::id();

        if ($senderId && $receiverId) {
            $messages = Message::with(['sender.profile', 'receiver.profile'])
                ->where(function ($q) use ($senderId, $receiverId) {
                    $q->where('sender_id', $senderId)->where('receiver_id', $receiverId)
                        ->orWhere(function ($q2) use ($senderId, $receiverId) {
                            $q2->where('sender_id', $receiverId)->where('receiver_id', $senderId);
                        });
                })
                ->orderBy('TimeForMessage', 'asc')
                ->get();
        } else {
            $messages = Message::with(['sender.profile', 'receiver.profile'])
                ->where(function ($q) use ($userId) {
                    $q->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
                })
                ->orderBy('TimeForMessage', 'desc')
                ->get();
        }

        $messages = $messages->map(function ($msg) use ($userId) {
            $msg->from = $msg->sender_id == $userId ? 'me' : 'owner';
            return $msg;
        });

        return response()->json($messages);
    }


    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
            'TimeForMessage' => 'required|date',
        ]);

        $validated['sender_id'] = $userId;

        $message = Message::create($validated);

        return response()->json($message, 201);
    }


    public function show($id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        $userId = Auth::id();
        if ($message->sender_id != $userId && $message->receiver_id != $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($message);
    }


    // public function update(Request $request, $id)
    // {
    //     $message = Message::find($id);
    //     if (!$message) {
    //         return response()->json(['message' => 'Message not found'], 404);
    //     }


    //     if ($message->sender_id != Auth::id()) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     $validated = $request->validate([
    //         'content' => 'sometimes|string',
    //         'TimeForMessage' => 'sometimes|date',
    //     ]);

    //     $message->update($validated);
    //     return response()->json($message);
    // }


    // public function destroy($id)
    // {
    //     $message = Message::find($id);
    //     if (!$message) {
    //         return response()->json(['message' => 'Message not found'], 404);
    //     }

    //     $userId = Auth::id();
    //     if ($message->sender_id != $userId && $message->receiver_id != $userId) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     $message->delete();
    //     return response()->json(['message' => 'Message deleted']);
    // }


   public function recentMessages()
{
       $userId = Auth::id(); // أو حسب طريقة المصادقة عندك

    $messages = Message::where('sender_id', $userId)
        ->orWhere('receiver_id', $userId)
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'desc')
        ->get();

    if ($messages->isEmpty()) {
        return response()->json([]);
    }

    // اختيار آخر رسالة مع كل مستخدم تم التراسل معه
    $recent = $messages->unique(function ($msg) use ($userId) {
        return $msg->sender_id == $userId ? $msg->receiver_id : $msg->sender_id;
    })->values();

    return response()->json($recent);
}


}
