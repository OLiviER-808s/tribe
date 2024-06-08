<?php

namespace Tests\Feature;

use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            $this->setupConversation(true, $popTopic)->viewModel(), // Level 2 Direct Match
            $this->setupConversation(true, $basketballTopic)->viewModel(), // Level 1 Direct Match
            $this->setupConversation(true, $musicTopic)->viewModel(), // Parent Match
            $this->setupConversation(true, $rockTopic)->viewModel(), // Adjacent Match
            $this->setupConversation(true, $moviesTopic)->viewModel(), // No Match
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
        $this->setupConversation();
        $this->setupConversation(false);

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

    public function test_user_is_not_shown_conversations_they_have_joined()
    {
        $this->setupConversation();
        $this->setupConversation(true, null, $this->testUser);

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
