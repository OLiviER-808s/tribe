<?php

namespace Tests\Feature\Chat;

use App\Constants\ConstChatActions;
use App\Constants\ConstMedia;
use App\Constants\ConstMessageTypes;
use App\Constants\ConstStatus;
use App\Events\MessageSent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class ChatUpdateTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    public function test_admin_can_update_chat_name_and_photo()
    {
        Storage::fake('s3');
        Event::fake([ MessageSent::class ]);

        $chat = $this->setupChat($this->testUser, $this->otherUser);
        $photo = UploadedFile::fake()->create('chat_photo.jpg', 500);

        $response = $this->put(route('chat.update', [
            'uuid' => $chat->uuid
        ]), [
            'name' => 'edited name',
            'photo' => $photo
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('chats', [
            'id' => $chat->id,
            'name' => 'edited name'
        ]);
        $this->assertFileExists($chat->getFirstMedia(ConstMedia::CHAT_PHOTO)->getPath());

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
            'content' => 'changed the chat name to "edited name"',
            'status' => ConstStatus::MESSAGE_SENT,
            'type' => ConstMessageTypes::ACTION
        ]);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
            'content' => ConstChatActions::PHOTO_CHANGED,
            'status' => ConstStatus::MESSAGE_SENT,
            'type' => ConstMessageTypes::ACTION
        ]);

        Event::assertDispatched(MessageSent::class, 2);
    }

    public function test_user_who_is_not_admin_cannot_update_chat_name_and_photo()
    {
        Storage::fake('s3');
        Event::fake([ MessageSent::class ]);

        $chat = $this->setupChat($this->otherUser, $this->testUser);
        $photo = UploadedFile::fake()->create('chat_photo.jpg', 500);

        $response = $this->put(route('chat.update', [
            'uuid' => $chat->uuid
        ]), [
            'name' => 'edited name',
            'photo' => $photo
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertNotFound();

        $this->assertDatabaseHas('chats', [
            'id' => $chat->id,
            'name' => $chat->name
        ]);
    }
}
