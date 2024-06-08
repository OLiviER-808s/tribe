<?php

namespace Tests\Feature\Search;

use App\Models\CommonSearch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    public function test_search_loads_correct_results()
    {
        $conversation = $this->setupConversation();
        $conversation->title = 'test1';
        $conversation->save();

        $searchTerm = CommonSearch::factory()->create([
            'search_term' => 'test2',
        ]);
        $this->addSearches($searchTerm, 1);

        $user = User::factory()->create([
            'username' => 'test3',
        ]);
        $this->addSearches($user, 2);

        $response = $this->getJson('/search?query=test');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $data = $response->json()['results']['data'];

        $this->assertCount(3, $data);

        $this->assertSame($user->searchResultType, $data[0]['resultType']);
        $this->assertSame($searchTerm->searchResultType, $data[1]['resultType']);
        $this->assertSame($conversation->searchResultType, $data[2]['resultType']);
    }

    public function test_search_page_loads_correct_results()
    {
        $conversation = $this->setupConversation();
        $conversation->title = 'test1';
        $conversation->save();

        $user = User::factory()->create([
            'username' => 'test3',
        ]);
        $this->addSearches($user, 1);

        $response = $this->get('/search?query=test');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertInertia(
                fn ($page) => $page
                    ->component('Search')
                    ->has('results.data', 2)
                    ->where('searchQuery', 'test')
                    ->where('results.data.0.resultType', $user->searchResultType)
                    ->where('results.data.1.resultType', $conversation->searchResultType)
            );
    }

    public function test_visiting_search_page_adds_common_search_term()
    {
        $response = $this->get('/search?query=test');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertDatabaseHas('common_searches', [
            'search_term' => 'test',
        ]);

        $this->assertDatabaseHas('searchables', [
            'searchable_type' => 'App\Models\CommonSearch',
            'searchable_id' => 1,
            'user_id' => $this->testUser->id,
        ]);
    }

    public function test_visiting_search_term_is_not_added_twice()
    {
        $searchTerm = CommonSearch::factory()->create([
            'search_term' => 'test',
        ]);
        $searchTerm->addSearchRecord($this->testUser->id);

        $response = $this->get('/search?query=test');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertDatabaseCount('common_searches', 1);
        $this->assertDatabaseCount('searchables', 1);
    }
}
