<?php

namespace Tests\Feature\Conversation;

use App\Constants\ConstTypes;
use App\Models\ReportCategory;
use Carbon\Carbon;
use Database\Seeders\ReportCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class ConversationReportTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    public function test_user_can_get_report_categories()
    {
        ReportCategory::factory()->count(5)->create([
            'type' => ConstTypes::CONVERSATION
        ]);

        $response = $this->get(route('report-categories', [
            'type' => ConstTypes::CONVERSATION
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $data = $response->json();
        $this->assertCount(5, $data['categories']);
    }

    public function test_user_can_report_conversation()
    {
        $conversation = $this->setupConversation();
        $category = ReportCategory::factory()->create([
            'type' => ConstTypes::CONVERSATION
        ]);

        $response = $this->post(route('conversation.report', [
            'uuid' => $conversation->uuid
        ]), [
            'uuid' => strval($category->uuid)
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertDatabaseHas('reports', [
            'reportable_type' => 'App\Models\Conversation',
            'reportable_id' => $conversation->id,
            'user_id' => $this->testUser->id,
            'category_id' => $category->id,
            'resolved' => false,
            'created_at' => Carbon::now()
        ]);
    }

    public function test_user_cannot_report_conversation_for_different_category_reason()
    {
        $conversation = $this->setupConversation();
        $category = ReportCategory::factory()->create();

        $response = $this->post(route('conversation.report', [
            'uuid' => $conversation->uuid
        ]), [
            'uuid' => strval($category->uuid)
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertNotFound();

        $this->assertDatabaseCount('reports', 0);
    }
}
