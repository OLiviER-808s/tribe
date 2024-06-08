<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;
use Tests\Traits\UsesTestHelpers;

class ProfileViewTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup, UsesTestHelpers;

    public function test_profile_page_loads_and_search_record_is_added()
    {
        $response = $this->get(route('profile', [
            'username' => $this->otherUser->username,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();

        $this->assertSame(1, $this->otherUser->search_count);

        $this->assertDatabaseHas('searchables', [
            'searchable_type' => 'App\Models\User',
            'searchable_id' => $this->otherUser->id,
            'user_id' => $this->testUser->id
        ]);
    }
}
