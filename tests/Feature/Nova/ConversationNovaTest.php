<?php

namespace Tests\Feature\Nova;

use App\Constants\ConstTypes;
use App\Models\Conversation;
use App\Models\ReportCategory;
use App\Models\User;
use App\Nova\Actions\ResolveReportedConversation;
use App\Nova\Lenses\ReportedConversations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tests\TestCase;
use Tests\Traits\UsesNovaTestSetup;
use Tests\Traits\UsesTestHelpers;

class ConversationNovaTest extends TestCase
{
    use RefreshDatabase, UsesNovaTestSetup, UsesTestHelpers;

    public function test_conversations_page_loads()
    {
        $response = $this->get('/nova-api/conversations');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();
    }

    public function test_conversations_to_review_lens_loads()
    {
        $conversation = $this->setupConversation();
        $conversation->reports()->create([
            'user_id' => User::factory()->create()->id,
            'category_id' => ReportCategory::factory()->create()->id,
        ]);

        $request = new LensRequest();
        $query = ReportedConversations::query($request, Conversation::query());

        $this->assertCount(1, $query->get());
    }

    public function test_lens_doesnt_load_inactive_conversations()
    {
        $conversation = $this->setupConversation(false);
        $conversation->reports()->create([
            'user_id' => User::factory()->create()->id,
            'category_id' => ReportCategory::factory()->create()->id,
        ]);

        $request = new LensRequest();
        $query = ReportedConversations::query($request, Conversation::query());

        $this->assertCount(0, $query->get());
    }

    public function test_lens_doesnt_conversations_without_reports()
    {
        $this->setupConversation();

        $request = new LensRequest();
        $query = ReportedConversations::query($request, Conversation::query());

        $this->assertCount(0, $query->get());
    }

    public function test_user_can_resolve_reported_conversation_without_deactivating_conversation()
    {
        $category = ReportCategory::factory()->create([
            'type' => ConstTypes::CONVERSATION,
        ]);
        $user = User::factory()->create();

        $conversation = $this->setupConversation();
        $conversation->reports()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $request = new NovaRequest();
        $action = new ResolveReportedConversation();

        $action->fields($request);
        $action->handle(
            new ActionFields(collect(['deactivate' => false]), collect()),
            collect([$conversation])
        );

        $this->assertDatabaseHas('reports', [
            'reportable_type' => 'App\Models\Conversation',
            'reportable_id' => $conversation->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'resolved' => true,
        ]);
    }

    public function test_user_can_resolve_reported_conversation_and_deactivate_conversation()
    {
        $category = ReportCategory::factory()->create([
            'type' => ConstTypes::CONVERSATION,
        ]);
        $user = User::factory()->create();

        $conversation = $this->setupConversation();
        $conversation->reports()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $request = new NovaRequest();
        $action = new ResolveReportedConversation();

        $action->fields($request);
        $action->handle(
            new ActionFields(collect(['deactivate' => false]), collect()),
            collect([$conversation])
        );

        $this->assertDatabaseHas('reports', [
            'reportable_type' => 'App\Models\Conversation',
            'reportable_id' => $conversation->id,
            'user_id' => $user->id,
            'category_id' => $category->id,
            'resolved' => true,
        ]);

        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
            'active' => true,
        ]);
    }
}
