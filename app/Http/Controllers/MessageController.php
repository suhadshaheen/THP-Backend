<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessageController extends Controller
{

    public function index(Request $request)
    {
        $senderId = $request->query('sender_id');
        $receiverId = $request->query('receiver_id');
        $userId = Auth::id();

        if ($senderId && $receiverId) {
            $messages = Message::with(['sender.profile', 'receiver.profile'])->where(function ($q) use ($senderId, $receiverId) {
                        $q->where('sender_id', $senderId)->where('receiver_id', $receiverId)
                            ->orWhere(function ($q2) use ($senderId, $receiverId) {
                                $q2->where('sender_id', $receiverId)->where('receiver_id', $senderId);
                            });
                    })->orderBy('TimeForMessage', 'asc')->get();
        } else {

            $messages = Message::with(['sender.profile', 'receiver.profile'])
                ->where(function ($q) use ($userId) {
                    $q->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
                })->orderBy('TimeForMessage', 'desc')->get();
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
        ]);

        $message = Message::create([
            'sender_id' => $userId,
            'receiver_id' => $validated['receiver_id'],
            'content' => $validated['content'],
            'TimeForMessage' => now(),
        ]);

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


    public function recentContacts()
    {
        $userId = Auth::id();
        $user = JWTAuth::user()->load('role');

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


        $contacts = User::where('role_id', $targetRoleId)->where('id', '!=', $userId)->with('profile')->orderBy('username')->get();

        return response()->json($contacts);
    }


    public function getConversationMessages($receiverId)
    {
        $userId = Auth::id();

        $messages = Message::with(['sender.profile', 'receiver.profile'])->where(function ($query) use ($userId, $receiverId) {
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
