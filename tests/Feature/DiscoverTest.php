<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $popConversation = $this->setupConversation($popTopic);
        $musicConversation = $this->setupConversation($musicTopic);
        $basketballConversation = $this->setupConversation($basketballTopic);
        $moviesConversation = $this->setupConversation($moviesTopic);

        $this->be($this->testUser);
        $response = $this->get(route('discover'));

        $response->assertSessionHasNoErrors();
        $response->assertOk();
    }
}
