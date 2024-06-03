<?php

namespace Tests\Feature\Chat;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class ChatTest extends TestCase
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
}
