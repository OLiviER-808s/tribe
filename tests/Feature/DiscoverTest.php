<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Assert;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;

class DiscoverTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup;

    public function test_discover_page_returns_conversations_in_the_correct_order(): void
    {
        $popTopic = Topic::where('label', 'Pop')->first();
        $musicTopic = Topic::where('label', 'Music')->first();
        $basketballTopic = Topic::where('label', 'Basketball')->first();
        $moviesTopic = Topic::where('label', 'Movies')->first();

        $this->testUser->interests()->sync([$popTopic->id, $musicTopic->id, $basketballTopic->id]);

        $expectedConversations = [
            $this->setupConversation($popTopic)->viewModel(),
            $this->setupConversation($musicTopic)->viewModel(),
            $this->setupConversation($basketballTopic)->viewModel(),
            $this->setupConversation($moviesTopic)->viewModel(),
        ];

        $this->be($this->testUser);
        $response = $this->get(route('discover'));

        $response->assertSessionHasNoErrors();
        $response->assertOk();

        $response->assertInertia(
            fn ($page) => $page
                ->component('Discover')
                ->has('conversations.data', 4)
                ->where('conversations.data.0.uuid', $expectedConversations[0]['uuid'])
                ->where('conversations.data.1.uuid', $expectedConversations[1]['uuid'])
                ->where('conversations.data.2.uuid', $expectedConversations[2]['uuid'])
                ->where('conversations.data.3.uuid', $expectedConversations[3]['uuid'])
        );
    }
}
