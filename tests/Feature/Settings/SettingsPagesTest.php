<?php

namespace Tests\Feature\Settings;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;

class SettingsPagesTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup;

    public function test_settings_index_page_loads()
    {
        $response = $this->get(route('settings'));

        $response->assertSessionHasNoErrors();
        $response->assertOk();
    }

    public function test_profile_settings_page_loads()
    {
        $response = $this->get(route('settings.profile'));

        $response->assertSessionHasNoErrors();
        $response->assertOk();
    }

    public function test_account_settings_page_loads()
    {
        $response = $this->get(route('settings.account'));

        $response->assertSessionHasNoErrors();
        $response->assertOk();
    }
}
