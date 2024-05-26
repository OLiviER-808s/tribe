<?php

namespace Settings;

use App\Models\UserSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;

class UserSettingsTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup;

    public function test_user_can_enable_dark_mode()
    {
        $response = $this->patch(route('settings.theme', [
            'theme' => 'dark'
        ]));

        $response->assertSessionHasNoErrors();
        $response->assertOk();

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $this->testUser->id,
            'theme' => 'dark'
        ]);
    }

    public function test_user_can_disable_dark_mode()
    {
        UserSetting::factory()->create([
            'user_id' => $this->testUser->id,
            'theme' => 'dark'
        ]);

        $response = $this->patch(route('settings.theme', [
            'theme' => 'light'
        ]));

        $response->assertSessionHasNoErrors();
        $response->assertOk();

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $this->testUser->id,
            'theme' => 'light'
        ]);
    }
}
