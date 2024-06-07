<?php

namespace Tests\Feature\Chat;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class ChatViewTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    public function test_chat_list_page_loads_correct_chats()
    {
        $this->setupChat($this->testUser);
        $this->setupChat($this->testUser, $this->otherUser);
        $this->setupChat($this->otherUser);

        $response = $this->get(route('chats'));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertInertia(
                fn ($page) => $page
                    ->component('Chats/ChatsIndex')
                    ->has('chats', 2)
            );
    }

    public function test_user_can_view_chat_they_are_a_part_of()
    {
        $chat = $this->setupChat($this->testUser);
        Message::factory()->count(3)->create([
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id
        ]);

        $response = $this->get(route('chat.show', [
            'uuid' => $chat->uuid
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertInertia(
                fn ($page) => $page
                    ->component('Chats/Chat')
                    ->has('messages', 3)
                    ->where('chat', $chat->viewModel())
            );
    }

    public function test_user_cannot_view_chat_they_are_not_a_part_of()
    {
        $chat = $this->setupChat($this->otherUser);

        $response = $this->get(route('chat.show', [
            'uuid' => $chat->uuid
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertNotFound();
    }

    public function test_last_read_message_is_updated_when_user_opens_chat()
    {
        $chat = $this->setupChat($this->testUser, $this->otherUser);
        $latestMessage = $this->addMessages($chat, $this->otherUser)->last();

        $response = $this->get(route('chat.show', [
            'uuid' => $chat->uuid
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $member = $chat->members->where('user_id', $this->testUser->id)->first();

        $this->assertDatabaseHas('chat_members', [
            'id' => $member->id,
            'uuid' => $member->uuid,
            'last_read_message_id' => $latestMessage->id
        ]);
    }
}
