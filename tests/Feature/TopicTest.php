<?php

namespace Tests\Feature;

use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;

class TopicTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup;

    public function test_user_can_search_topics()
    {
        $topic = Topic::where('label', 'Movies')->first();

        $response = $this->get('/topics?search=movies');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertJson([
                'topics' => [
                    $topic->viewModel(),
                ],
            ]);
    }

    public function test_inactive_topics_do_not_appear_in_search()
    {
        Topic::factory()->create([
            'label' => 'inactive',
            'active' => false,
        ]);

        $response = $this->get('/topics?search=inactive');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertJson([
                'topics' => [],
            ]);
    }

    public function test_user_can_get_child_topics()
    {
        $topic = Topic::where('label', 'Music')->with('children')->first();

        $response = $this->get(route('topic.children', [
            'uuid' => $topic->uuid,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertJson([
                'topics' => $topic->children?->map(fn ($topic) => $topic->viewModel())->toArray(),
            ]);
    }

    public function test_user_can_add_topic()
    {
        $response = $this->post(route('topic.store'), [
            'label' => 'test',
        ]);

        $topic = Topic::where('label', 'test')->first();

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertJson([
                'topic' => $topic->viewModel(),
            ]);

        $this->assertDatabaseHas('topics', [
            'id' => $topic->id,
            'uuid' => $topic->uuid,
            'label' => 'test',
            'active' => false,
            'requested_by_id' => $this->testUser->id,
            'category_id' => null,
            'parent_id' => null,
        ]);
    }

    public function test_user_cannot_add_topic_without_label()
    {
        $response = $this->post(route('topic.store'), [
            'label' => '',
        ]);

        $response
            ->assertSessionHasErrors(['label'])
            ->assertRedirect();
    }
}
