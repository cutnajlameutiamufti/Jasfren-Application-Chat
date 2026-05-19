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
        return view('groups.create');
    }

    public function store(Request $request)
    {
        // Placeholder
    }

    public function show($id)
    {
        // Placeholder
    }
}
