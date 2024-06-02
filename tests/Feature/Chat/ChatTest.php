<?php

namespace Tests\Feature\Chat;

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
}
