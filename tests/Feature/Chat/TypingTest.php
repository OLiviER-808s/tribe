<?php

namespace Tests\Feature\Chat;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class TypingTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    private Chat $chat;

    protected function extraSetup(): void
    {
        Event::fake([ UserTyping::class ]);
        $this->chat = $this->setupChat($this->testUser, $this->otherUser);
    }

    public function test_user_can_start_typing_message()
    {
        $response = $this->post(route('chat.typing', [
            'uuid' => $this->chat->uuid
        ]), [
            'typing' => true
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        Event::assertDispatched(UserTyping::class);
    }

    public function test_can_stop_typing_message()
    {
        $response = $this->post(route('chat.typing', [
            'uuid' => $this->chat->uuid
        ]), [
            'typing' => false
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        Event::assertDispatched(UserTyping::class);
    }
}
