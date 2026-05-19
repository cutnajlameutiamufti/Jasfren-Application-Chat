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
