<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;

class ProfileTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_data_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'test name',
                'username' => 'test_username',
                'bio' => 'test bio',
                'location' => 'test location',
                'next_route' => 'settings.profile'
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('settings.profile'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'test name',
            'username' => 'test_username',
            'bio' => 'test bio',
            'location' => 'test location',
            'email_verified_at' => $user->email_verified_at
        ]);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete(route('profile.destroy'), [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/register');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('settings.account'))
            ->delete(route('profile.destroy'), [
                'password' => 'wrong-password',
            ]);

        $response->assertSessionHasErrors('password');
        $response->assertRedirect(route('settings.account'));

        $this->assertNotNull($user->fresh());
    }
}
