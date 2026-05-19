<?php

use App\Models\User;
use App\Models\Group;
use App\Models\GroupMessage;

test('guests are redirected to login from groups page', function () {
    $response = $this->get(route('groups.index'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can access groups page and see their groups', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    // Create a group
    $group = Group::create([
        'name' => 'Keluarga Cemara',
        'created_by' => $user->id,
    ]);

    // Attach both users to the group
    $group->members()->attach([$user->id, $otherUser->id]);

    // Create a message inside the group
    GroupMessage::create([
        'group_id' => $group->id,
        'sender_id' => $otherUser->id,
        'body' => 'Kabar baik semuanya!',
    ]);

    $response = $this->actingAs($user)
        ->get(route('groups.index'));

    $response->assertSuccessful();
    $response->assertSee('Keluarga Cemara');
    $response->assertSee('Kabar baik semuanya!');
    $response->assertSee('2 Anggota');
});

test('groups page shows empty state when user has no groups', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('groups.index'));

    $response->assertSuccessful();
    $response->assertSee('Belum Ada Grup');
    $response->assertSee('Buat grup baru dan undang teman untuk mulai berdiskusi bersama.');
});
