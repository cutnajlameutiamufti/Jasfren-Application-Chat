<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Group;

class GroupController extends Controller
{
    public function index()
    {
        // Ambil semua grup yang diikuti oleh user aktif beserta pesan terakhirnya
        $groups = auth()->user()->groups()
            ->with(['latestMessage.sender'])
            ->get()
            ->sortByDesc(function ($group) {
                return $group->latestMessage ? $group->latestMessage->created_at : $group->created_at;
            });

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $users = \App\Models\User::where('id', '!=', auth()->id())->orderBy('name', 'asc')->get();
        return view('groups.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:users,id',
        ], [
            'name.required' => 'Nama grup wajib diisi.',
            'members.required' => 'Pilih minimal satu anggota grup.',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        // Gabungkan anggota pilihan dengan pencipta grup
        $memberIds = array_unique(array_merge($request->members, [auth()->id()]));
        $group->members()->attach($memberIds);

        return redirect()->route('groups.show', $group->id);
    }

    public function show($id)
    {
        $activeGroup = Group::with('members')->findOrFail($id);

        // Keamanan: pastikan user aktif adalah anggota grup ini
        if (!$activeGroup->members->contains(auth()->id())) {
            abort(403, 'Anda bukan anggota grup ini.');
        }

        // Ambil semua grup untuk sidebar kiri
        $groups = auth()->user()->groups()
            ->with(['latestMessage.sender'])
            ->get()
            ->sortByDesc(function ($g) {
                return $g->latestMessage ? $g->latestMessage->created_at : $g->created_at;
            });

        // Ambil semua pesan dalam grup ini beserta info pengirimnya
        $messages = $activeGroup->messages()->with('sender')->orderBy('created_at', 'asc')->get();

        return view('groups.show', compact('activeGroup', 'groups', 'messages'));
    }
}
