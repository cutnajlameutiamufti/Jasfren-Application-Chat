<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Message;

class ChatController extends Controller
{
    public function index()
    {
        $authId = auth()->id();

        // Ambil ID semua user yang pernah berkirim pesan dengan kita
        $sentMessageUserIds = Message::where('sender_id', $authId)
            ->pluck('receiver_id')
            ->toArray();

        $receivedMessageUserIds = Message::where('receiver_id', $authId)
            ->pluck('sender_id')
            ->toArray();

        // Gabungkan dan hilangkan duplikasi, pastikan ID kita sendiri tidak masuk
        $userIds = array_unique(array_merge($sentMessageUserIds, $receivedMessageUserIds));
        $userIds = array_filter($userIds, fn($id) => $id != $authId);

        // Ambil data User beserta pesan terakhir mereka
        $chats = User::whereIn('id', $userIds)->get()->map(function ($user) use ($authId) {
            // Ambil pesan terakhir antara auth user dan user ini
            $lastMessage = Message::where(function ($query) use ($authId, $user) {
                $query->where('sender_id', $authId)->where('receiver_id', $user->id);
            })->orWhere(function ($query) use ($authId, $user) {
                $query->where('sender_id', $user->id)->where('receiver_id', $authId);
            })->latest()->first();

            // Hitung jumlah pesan belum dibaca dari user ini
            $unreadCount = Message::where('sender_id', $user->id)
                ->where('receiver_id', $authId)
                ->where('is_read', false)
                ->count();

            return [
                'user' => $user,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
            ];
        })->sortByDesc(function ($chat) {
            return $chat['last_message']?->created_at?->timestamp ?? 0;
        });

        return view('chats.index', compact('chats'));
    }

    public function create()
    {
        return view('chats.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|exists:users,username',
        ], [
            'username.exists' => 'Username tidak ditemukan.',
        ]);

        $username = $request->username;

        if ($username === auth()->user()->username) {
            return back()->withErrors(['username' => 'Anda tidak bisa memulai chat dengan diri sendiri.']);
        }

        return redirect()->route('chats.show', $username);
    }

    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        return view('chats.show', compact('user'));
    }
}
