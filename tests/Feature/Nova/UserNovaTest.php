<?php

namespace Tests\Feature\Nova;

use App\Models\Topic;
use App\Nova\Lenses\TopicsToReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Nova\Http\Requests\LensRequest;
use Tests\TestCase;
use Tests\Traits\UsesNovaTestSetup;

class UserNovaTest extends TestCase
{
    use RefreshDatabase, UsesNovaTestSetup;

    public function test_admin_users_resource_page_loads()
    {
        $response = $this->get('/nova-api/admin-users');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();
    }

    public function test_users_resource_page_loads()
    {
        $response = $this->get('/nova-api/users');

        $response
            ->assertSessionHasNoErrors()
            ->assertOk();
    }
}
