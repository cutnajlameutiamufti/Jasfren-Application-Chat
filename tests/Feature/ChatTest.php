<?php

use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests cannot access personal chats', function () {
    $this->get(route('chats.index'))
        ->assertRedirect(route('login'));
});

test('authenticated users can access personal chats and see chat list', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    // Create a message between them
    Message::create([
        'sender_id' => $user->id,
        'receiver_id' => $otherUser->id,
        'body' => 'Halo kawan!',
        'is_read' => false,
    ]);

    $response = $this->actingAs($user)
        ->get(route('chats.index'));

    $response->assertSuccessful();
    $response->assertSee($otherUser->name);
    $response->assertSee('Halo kawan!');
});

test('authenticated users can access the create chat page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('chats.create'));

    $response->assertSuccessful();
    $response->assertSee('Mulai Chat Baru');
});

test('adding non-existent username returns validation error', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('chats.store'), [
            'username' => 'nonexistentuser',
        ]);

    $response->assertSessionHasErrors('username');
});

test('adding own username returns validation error', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('chats.store'), [
            'username' => $user->username,
        ]);

    $response->assertSessionHasErrors('username');
});

test('adding existing username redirects to chat room', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('chats.store'), [
            'username' => $otherUser->username,
        ]);

    $response->assertRedirect(route('chats.show', $otherUser->username));
});

test('authenticated users can access the chat room page and view messages', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    // Create some messages between them
    $msg1 = Message::create([
        'sender_id' => $user->id,
        'receiver_id' => $otherUser->id,
        'body' => 'Hai, apa kabar?',
        'is_read' => true,
    ]);

    $msg2 = Message::create([
        'sender_id' => $otherUser->id,
        'receiver_id' => $user->id,
        'body' => 'Kabar baik! Kamu?',
        'is_read' => false,
    ]);

    $response = $this->actingAs($user)
        ->get(route('chats.show', $otherUser->username));

    $response->assertSuccessful();
    $response->assertSee($otherUser->name);
    $response->assertSee($otherUser->username);
    $response->assertSee('Hai, apa kabar?');
    $response->assertSee('Kabar baik! Kamu?');
});
