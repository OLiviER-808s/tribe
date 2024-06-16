<?php

namespace Tests\Feature\Chat;

use App\Constants\ConstChatActions;
use App\Constants\ConstTypes;
use App\Constants\ConstStatus;
use App\Events\MessageSent;
use App\Models\ChatMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class ChatMemberTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    public function test_user_can_leave_chat()
    {
        Event::fake([MessageSent::class]);
        $chat = $this->setupChat($this->otherUser, $this->testUser);

        $response = $this->delete(route('chat.leave', [
            'uuid' => $chat->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('chats'));

        $this->assertSoftDeleted('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
        ]);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
            'content' => ConstChatActions::USER_LEFT,
            'status' => ConstStatus::MESSAGE_SENT,
            'type' => ConstTypes::ACTION,
        ]);

        Event::assertDispatched(MessageSent::class);
    }

    public function test_user_can_archive_chat()
    {
        $chat = $this->setupChat($this->otherUser, $this->testUser);

        $response = $this->patch(route('chat.archive', [
            'uuid' => $chat->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('chats'));

        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
            'archived' => true,
        ]);
    }

    public function test_user_can_unarchive_chat()
    {
        $chat = $this->setupChat($this->otherUser, $this->testUser);

        $response = $this->patch(route('chat.unarchive', [
            'uuid' => $chat->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
            'archived' => false,
        ]);
    }

    public function test_admin_can_remove_regular_users_from_chat()
    {
        Event::fake([MessageSent::class]);

        $chat = $this->setupChat($this->testUser, $this->otherUser);
        $member = $chat->members->where('user_id', $this->otherUser->id)->first();

        $response = $this->delete(route('chat.remove-member', [
            'chatUuid' => $chat->uuid,
            'memberUuid' => $member->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertSoftDeleted('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $this->otherUser->id,
        ]);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
            'content' => 'removed '.$this->otherUser->name.' from the chat',
            'status' => ConstStatus::MESSAGE_SENT,
            'type' => ConstTypes::ACTION,
        ]);

        Event::assertDispatched(MessageSent::class);
    }

    public function test_user_who_is_not_admin_cannot_remove_users_from_chat()
    {
        $chat = $this->setupChat($this->otherUser, $this->testUser);
        $member = $chat->members->where('user_id', $this->otherUser->id)->first();

        $response = $this->delete(route('chat.remove-member', [
            'chatUuid' => $chat->uuid,
            'memberUuid' => $member->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertNotFound();

        $this->assertNotSoftDeleted('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $this->otherUser->id,
        ]);
    }

    public function test_admin_cannot_remove_other_admin_from_chat()
    {
        $chat = $this->setupChat($this->testUser);

        $member = ChatMember::factory()->create([
            'chat_id' => $chat->id,
            'user_id' => $this->otherUser->id,
            'admin' => true,
        ]);

        $response = $this->delete(route('chat.remove-member', [
            'chatUuid' => $chat->uuid,
            'memberUuid' => $member->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertNotFound();

        $this->assertNotSoftDeleted('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $this->otherUser->id,
        ]);
    }

    public function test_admin_can_assign_admin_to_another_user()
    {
        $chat = $this->setupChat($this->testUser, $this->otherUser);
        $member = $chat->members->where('user_id', $this->otherUser->id)->first();

        $response = $this->patch(route('chat.assign-admin', [
            'chatUuid' => $chat->uuid,
            'memberUuid' => $member->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $this->otherUser->id,
            'admin' => true,
        ]);
    }

    public function test_regular_user_cannot_assign_admin_to_another_user()
    {
        $userThree = User::factory()->create();

        $chat = $this->setupChat($this->otherUser, $this->testUser, $userThree);
        $member = $chat->members->where('user_id', $userThree->id)->first();

        $response = $this->patch(route('chat.assign-admin', [
            'chatUuid' => $chat->uuid,
            'memberUuid' => $member->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertNotFound();

        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $userThree->id,
            'admin' => false,
        ]);
    }
}
