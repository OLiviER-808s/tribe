<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Assert;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class DiscoverTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    public function test_discover_page_returns_conversations_in_the_correct_order(): void
    {
        $popTopic = Topic::where('label', 'Pop')->first();
        $musicTopic = Topic::where('label', 'Music')->first();
        $basketballTopic = Topic::where('label', 'Basketball')->first();
        $rockTopic = Topic::where('label', 'Rock')->first();
        $moviesTopic = Topic::where('label', 'Movies')->first();

        $this->testUser->interests()->sync([$popTopic->id, $basketballTopic->id]);

        $expectedConversations = [
            $this->setupConversation($popTopic)->viewModel(), // Level 2 Direct Match
            $this->setupConversation($basketballTopic)->viewModel(), // Level 1 Direct Match
            $this->setupConversation($musicTopic)->viewModel(), // Parent Match
            $this->setupConversation($rockTopic)->viewModel(), // Adjacent Match
            $this->setupConversation($moviesTopic)->viewModel(), // No Match
        ];

        $response = $this->get(route('discover'));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertInertia(
                fn ($page) => $page
                    ->component('Discover')
                    ->has('conversations.data', 5)
                    ->where('conversations.data.0.uuid', $expectedConversations[0]['uuid'])
                    ->where('conversations.data.1.uuid', $expectedConversations[1]['uuid'])
                    ->where('conversations.data.2.uuid', $expectedConversations[2]['uuid'])
                    ->where('conversations.data.3.uuid', $expectedConversations[3]['uuid'])
                    ->where('conversations.data.4.uuid', $expectedConversations[4]['uuid'])
            );
    }

    public function test_inactive_conversations_are_not_shown_in_discover()
    {
        $musicTopic = Topic::where('label', 'Music')->first();

        $this->setupConversation($musicTopic);
        $this->setupConversation($musicTopic, false);

        $response = $this->get(route('discover'));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertInertia(
                fn ($page) => $page
                    ->component('Discover')
                    ->has('conversations.data', 1)
            );
    }
}
