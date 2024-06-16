<?php

namespace Tests\Feature\Conversation;

use App\Constants\ConstChatActions;
use App\Constants\ConstTypes;
use App\Constants\ConstStatus;
use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Conversation;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;

class ConversationCreateTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup;

    private const TEST_STRING = 'test';

    public function test_create_conversation_page_loads()
    {
        $response = $this->get(route('conversation.create'));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();
    }

    public function test_user_can_create_conversation()
    {
        Event::fake([MessageSent::class]);
        $topic = Topic::all()->random();

        $response = $this->post(route('conversation.store'), [
            'title' => self::TEST_STRING,
            'description' => self::TEST_STRING,
            'limit' => 5,
            'topic' => $topic->uuid,
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('conversations', [
            'title' => self::TEST_STRING,
            'description' => self::TEST_STRING,
            'limit' => 5,
            'topic_id' => $topic->id,
            'active' => true,
            'created_by_id' => $this->testUser->id,
            'created_at' => Carbon::now(),
        ]);

        $conversation = Conversation::first();

        $this->assertDatabaseHas('chats', [
            'name' => self::TEST_STRING,
            'conversation_id' => $conversation->id,
            'created_at' => Carbon::now(),
        ]);

        $chat = Chat::first();

        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
            'admin' => true,
            'archived' => false,
            'last_read_message_id' => null,
            'created_at' => Carbon::now(),
        ]);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
            'content' => ConstChatActions::CHAT_CREATED,
            'status' => ConstStatus::MESSAGE_SENT,
            'type' => ConstTypes::ACTION,
        ]);

        Event::assertDispatched(MessageSent::class);
    }

    public function test_user_cannot_create_conversation_without_filling_in_required_fields()
    {
        $response = $this->post(route('conversation.store'));

        $response
            ->assertSessionHasErrors()
            ->assertRedirect();

        $this->assertDatabaseCount('conversations', 0);
        $this->assertDatabaseCount('chats', 0);
        $this->assertDatabaseCount('chat_members', 0);
        $this->assertDatabaseCount('messages', 0);
    }
}
