<?php

namespace Tests\Feature\Conversation;

use App\Constants\ConstChatActions;
use App\Constants\ConstStatus;
use App\Constants\ConstTypes;
use App\Events\MessageSent;
use App\Models\ChatMember;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class ConversationJoinTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    public function test_user_can_join_conversation()
    {
        Event::fake([MessageSent::class]);

        $conversation = $this->setupConversation();
        $latestMessage = $this->addMessages($conversation->chat)->last();

        $response = $this->post(route('conversation.join', [
            'uuid' => $conversation->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $conversation->chat->id,
            'user_id' => $this->testUser->id,
            'admin' => false,
            'archived' => false,
            'last_read_message_id' => $latestMessage->id,
            'created_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $conversation->chat->id,
            'user_id' => $this->testUser->id,
            'content' => ConstChatActions::USER_JOINED,
            'status' => ConstStatus::MESSAGE_SENT,
            'type' => ConstTypes::ACTION,
        ]);

        Event::assertDispatched(MessageSent::class);
    }

    public function test_conversation_is_made_inactive_once_limit_is_reached()
    {
        Event::fake([MessageSent::class]);
        $conversation = $this->setupConversation(true, null, $this->otherUser, 2);

        $response = $this->post(route('conversation.join', [
            'uuid' => $conversation->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
            'uuid' => $conversation->uuid,
            'active' => false,
        ]);
    }

    public function test_user_cannot_join_inactive_conversation()
    {
        $conversation = $this->setupConversation(false);

        $response = $this->post(route('conversation.join', [
            'uuid' => $conversation->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertNotFound();

        $this->assertDatabaseCount('chat_members', 1);
    }

    public function test_user_cannot_join_conversation_twice()
    {
        $conversation = $this->setupConversation(true, null, $this->testUser);

        $response = $this->post(route('conversation.join', [
            'uuid' => $conversation->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertNotFound();

        $this->assertDatabaseCount('chat_members', 1);
    }

    public function test_user_cannot_rejoin_conversation_after_leaving()
    {
        $conversation = $this->setupConversation(true, null, $this->otherUser);

        ChatMember::factory()->create([
            'chat_id' => $conversation->chat->id,
            'user_id' => $this->testUser->id,
            'deleted_at' => Carbon::now(),
        ]);

        $response = $this->post(route('conversation.join', [
            'uuid' => $conversation->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertNotFound();

        $this->assertDatabaseCount('chat_members', 2);

        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $conversation->chat->id,
            'user_id' => $this->testUser->id,
            'deleted_at' => Carbon::now(),
        ]);
    }
}
