<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessageController extends Controller
{

    public function store(Request $request)
{
    $user = JWTAuth::user();
    $userId = $user->id;

    $validated = $request->validate([
        'receiver_id' => 'required|exists:users,id',
        'content' => 'required|string',
    ]);

    $message = Message::create([
        'sender_id' => $userId,
        'receiver_id' => $validated['receiver_id'],
        'content' => $validated['content'],
        'TimeForMessage' => now(),
    ]);

    return response()->json($message, 201);
}

    public function recentContacts()
    {
       $user = JWTAuth::user()->load('role');
$userId = $user->id;


        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $targetRoleId = null;
        if ($user->role?->name === 'FreeLancer') {
            $targetRole = Role::where('name', 'JobOwner')->first();
            if ($targetRole) {
                $targetRoleId = $targetRole->id;
            }
        } elseif ($user->role?->name === 'JobOwner') {
            $targetRole = Role::where('name', 'FreeLancer')->first();
            if ($targetRole) {
                $targetRoleId = $targetRole->id;
            }
        }

        if (!$targetRoleId) {
            return response()->json([]);
        }

        $contactIds = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get(['sender_id', 'receiver_id'])
            ->map(function ($message) use ($userId) {
                return ($message->sender_id == $userId) ? $message->receiver_id : $message->sender_id;
            })
            ->unique()
            ->values()
            ->reject(function ($id) use ($userId) {
                return $id == $userId;
            });

        $contacts = User::whereIn('id', $contactIds)
            ->where('role_id', $targetRoleId)
            ->with('profile')
            ->orderBy('username')
            ->get();

        return response()->json($contacts);
    }

    public function getConversationMessages($receiverId)
    {
        $user = JWTAuth::user();
$userId = $user->id;

$messages = Message::with(['sender.profile', 'receiver.profile'])
    ->where(function ($query) use ($userId, $receiverId) {
        $query->where(function ($q) use ($userId, $receiverId) {
            $q->where('sender_id', $userId)->where('receiver_id', $receiverId);
        })->orWhere(function ($q) use ($userId, $receiverId) {
            $q->where('sender_id', $receiverId)->where('receiver_id', $userId);
        });
    })->orderBy('TimeForMessage', 'asc')->get();

$messages = $messages->map(function ($msg) use ($userId) {
    $msg->from = $msg->sender_id == $userId ? 'me' : 'other';
    return $msg;
});

return response()->json($messages);

}
}
