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

test('authenticated users can access group creation page', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('groups.create'));

    $response->assertSuccessful();
    $response->assertSee('Buat Grup Baru');
    $response->assertSee($otherUser->name);
    $response->assertSee($otherUser->username);
});

test('authenticated users can create a new group successfully', function () {
    $user = User::factory()->create();
    $otherUser1 = User::factory()->create();
    $otherUser2 = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('groups.store'), [
            'name' => 'IT Project Team',
            'members' => [$otherUser1->id, $otherUser2->id],
        ]);

    // As our show route is currently a placeholder redirecting, let's verify redirect
    $response->assertRedirect();

    $this->assertDatabaseHas('groups', [
        'name' => 'IT Project Team',
        'created_by' => $user->id,
    ]);

    $group = Group::where('name', 'IT Project Team')->first();

    $this->assertDatabaseHas('group_user', [
        'group_id' => $group->id,
        'user_id' => $user->id,
    ]);
    
    $this->assertDatabaseHas('group_user', [
        'group_id' => $group->id,
        'user_id' => $otherUser1->id,
    ]);

    $this->assertDatabaseHas('group_user', [
        'group_id' => $group->id,
        'user_id' => $otherUser2->id,
    ]);
});

test('creating group with validation errors returns correct errors', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('groups.store'), [
            'name' => '',
            'members' => [],
        ]);

    $response->assertSessionHasErrors(['name', 'members']);
});

test('guests are redirected to login from group chat room', function () {
    $user = User::factory()->create();
    $group = Group::create([
        'name' => 'IT Project Team',
        'created_by' => $user->id,
    ]);

    $response = $this->get(route('groups.show', $group->id));

    $response->assertRedirect(route('login'));
});

test('non members cannot access group chat room and get 403', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    
    $group = Group::create([
        'name' => 'IT Project Team',
        'created_by' => $otherUser->id,
    ]);
    $group->members()->attach($otherUser->id);

    $response = $this->actingAs($user)
        ->get(route('groups.show', $group->id));

    $response->assertStatus(403);
});

test('group members can access group chat room and view messages', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $group = Group::create([
        'name' => 'IT Project Team',
        'created_by' => $user->id,
    ]);
    $group->members()->attach([$user->id, $otherUser->id]);

    // Create a message in the group from otherUser
    $msg = GroupMessage::create([
        'group_id' => $group->id,
        'sender_id' => $otherUser->id,
        'body' => 'Halo tim! Semangat ya!',
    ]);

    $response = $this->actingAs($user)
        ->get(route('groups.show', $group->id));

    $response->assertSuccessful();
    $response->assertSee('IT Project Team');
    $response->assertSee('2 Anggota');
    $response->assertSee($otherUser->name);
    $response->assertSee('Halo tim! Semangat ya!');
});

test('group members can send group messages successfully', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $group = Group::create([
        'name' => 'Keluarga Cemara',
        'created_by' => $user->id,
    ]);
    $group->members()->attach([$user->id, $otherUser->id]);

    $response = $this->actingAs($user)
        ->post(route('groups.messages.store', $group->id), [
            'body' => 'Hallo semua, saya baru bergabung!',
        ]);

    $response->assertRedirect(route('groups.show', $group->id));

    $this->assertDatabaseHas('group_messages', [
        'group_id' => $group->id,
        'sender_id' => $user->id,
        'body' => 'Hallo semua, saya baru bergabung!',
    ]);
});

test('non members cannot send group messages and get 403', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $group = Group::create([
        'name' => 'Keluarga Cemara',
        'created_by' => $otherUser->id,
    ]);
    $group->members()->attach($otherUser->id);

    $response = $this->actingAs($user)
        ->post(route('groups.messages.store', $group->id), [
            'body' => 'Bolehkah saya ikut?',
        ]);

    $response->assertStatus(403);
    $this->assertDatabaseMissing('group_messages', [
        'group_id' => $group->id,
        'body' => 'Bolehkah saya ikut?',
    ]);
});

test('sending empty group message returns validation errors', function () {
    $user = User::factory()->create();

    $group = Group::create([
        'name' => 'Keluarga Cemara',
        'created_by' => $user->id,
    ]);
    $group->members()->attach($user->id);

    $response = $this->actingAs($user)
        ->post(route('groups.messages.store', $group->id), [
            'body' => '',
        ]);

    $response->assertSessionHasErrors(['body']);
});
