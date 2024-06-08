<?php

namespace Tests\Feature\Conversation;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class ConversationViewTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    public function test_conversation_page_loads_and_search_record_is_added()
    {
        $conversation = $this->setupConversation();

        $response = $this->get(route('conversation', [
            'uuid' => $conversation->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertSame(1, $conversation->search_count);

        $this->assertDatabaseHas('searchables', [
            'searchable_type' => 'App\Models\Conversation',
            'searchable_id' => $conversation->id,
            'user_id' => $this->testUser->id
        ]);
    }
}
