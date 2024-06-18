<?php

namespace Tests\Feature\Chat;

use App\Constants\ConstMedia;
use App\Constants\ConstStatus;
use App\Constants\ConstTypes;
use App\Events\MessageSent;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class MessageTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    private const TEST_MESSAGE = 'test message';

    public function test_user_can_send_message()
    {
        Event::fake([MessageSent::class]);
        $chat = $this->setupChat($this->testUser, $this->otherUser);

        $messageUuid = fake()->uuid();

        $response = $this->post(route('chat.send-message', [
            'uuid' => $chat->uuid,
        ]), [
            'uuid' => $messageUuid,
            'content' => self::TEST_MESSAGE,
            'active_uuids' => [],
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertDatabaseHas('messages', [
            'uuid' => $messageUuid,
            'chat_id' => $chat->id,
            'user_id' => $this->testUser->id,
            'content' => self::TEST_MESSAGE,
            'created_at' => Carbon::now(),
            'status' => ConstStatus::MESSAGE_SENT,
            'type' => ConstTypes::MESSAGE,
        ]);

        Event::assertDispatched(MessageSent::class);
    }

    public function test_last_read_message_is_updated_for_active_users_when_message_is_sent()
    {
        Event::fake([MessageSent::class]);

        $chat = $this->setupChat($this->testUser, $this->otherUser);
        $member = $chat->members->where('user_id', $this->otherUser->id)->first();

        $messageUuid = fake()->uuid();

        $response = $this->post(route('chat.send-message', [
            'uuid' => $chat->uuid,
        ]), [
            'uuid' => $messageUuid,
            'content' => self::TEST_MESSAGE,
            'active_uuids' => [$member->uuid],
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $message = $chat->messages->first();

        $this->assertDatabaseHas('chat_members', [
            'chat_id' => $chat->id,
            'user_id' => $this->otherUser->id,
            'last_read_message_id' => $message->id,
        ]);
    }

    public function test_user_can_send_message_with_files()
    {
        Storage::fake('s3');
        Event::fake([MessageSent::class]);

        $chat = $this->setupChat($this->testUser, $this->otherUser);

        $messageUuid = fake()->uuid();
        $file = UploadedFile::fake()->create('file.pdf', 500);

        $response = $this->post(route('chat.send-message', [
            'uuid' => $chat->uuid,
        ]), [
            'uuid' => $messageUuid,
            'content' => self::TEST_MESSAGE,
            'active_uuids' => [],
            'files' => [$file],
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $message = $chat->messages->first();

        $this->assertFileExists($message->getFirstMedia(ConstMedia::MESSAGE_ATTACHMENTS)->getPath());
    }

    public function test_user_download_message_attachments()
    {
        $chat = $this->setupChat($this->testUser, $this->otherUser);
        $message = $this->addMessages($chat, $this->otherUser, 1)->first();

        $file = UploadedFile::fake()->create('file.pdf', 500);
        $message->addMedia($file)->toMediaCollection(ConstMedia::MESSAGE_ATTACHMENTS);

        $media = $message->getFirstMedia(ConstMedia::MESSAGE_ATTACHMENTS);

        $response = $this->get(route('message.download-attachment', [
            'messageUuid' => $message->uuid,
            'fileUuid' => $media->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertDownload($media->file_name);
    }
}
