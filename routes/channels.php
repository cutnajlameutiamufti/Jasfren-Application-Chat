<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Saluran privat untuk chat personal (hanya user penerima/pemilik id yang bisa mendengar)
Broadcast::channel('chat.user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Saluran privat untuk chat grup (hanya anggota grup yang bisa mendengar)
Broadcast::channel('groups.{id}', function ($user, $id) {
    return $user->groups()->where('group_id', $id)->exists();
});
