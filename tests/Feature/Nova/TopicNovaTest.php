<?php

namespace Tests\Feature\Nova;

use App\Models\Topic;
use App\Nova\Lenses\TopicsToReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Nova\Http\Requests\LensRequest;
use Tests\TestCase;
use Tests\Traits\UsesNovaTestSetup;

class TopicNovaTest extends TestCase
{
    use RefreshDatabase, UsesNovaTestSetup;

    public function test_topic_category_resource_page_loads()
    {
        $response = $this->get('/nova-api/topic-categories');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();
    }

    public function test_topic_resource_page_loads()
    {
        $response = $this->get('/nova-api/topics');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();
    }

    public function test_topics_to_review_lens_loads()
    {
        Topic::factory()->count(5)->create([
            'active' => false
        ]);

        $request = new LensRequest();
        $query = TopicsToReview::query($request, Topic::query());

        $this->assertCount(5, $query->get());
    }
}
