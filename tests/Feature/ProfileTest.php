<?php

namespace Tests\Feature;

use App\Constants\ConstStatus;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;

class ProfileTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup;

    protected function extraSetup(): void
    {
        User::factory()->create([
            'email' => 'tribe.placeholder@tribe.com',
            'name' => 'Tribe User',
            'username' => 'tribe_user',
            'bio' => null,
            'password' => null,
            'location' => null,
            'status' => ConstStatus::USER_INACTIVE
        ]);
    }

    public function test_profile_data_can_be_updated(): void
    {
        $response = $this
            ->actingAs($this->testUser)
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
            'id' => $this->testUser->id,
            'name' => 'test name',
            'username' => 'test_username',
            'bio' => 'test bio',
            'location' => 'test location',
            'email_verified_at' => $this->testUser->email_verified_at
        ]);
    }

    public function test_user_can_update_their_interests()
    {
        $newInterests = Topic::inRandomOrder()->take(6)->pluck('uuid')->toArray();

        $response = $this
            ->actingAs($this->testUser)
            ->patch(route('profile.interests.update'), [
                'interests' => $newInterests,
                'next_route' => 'settings.profile'
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('settings.profile'));

        $this->testUser->refresh();
        $this->assertCount(6, $this->testUser->interests);

        foreach ($this->testUser->interests as $interest) {
            $this->assertContains($interest->uuid, $newInterests);
        }
    }

    public function test_user_can_delete_their_account(): void
    {
        $response = $this
            ->actingAs($this->testUser)
            ->delete(route('profile.destroy'), [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/register');

        $this->assertGuest();
        $this->assertNull($this->testUser->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $response = $this
            ->actingAs($this->testUser)
            ->from(route('settings.account'))
            ->delete(route('profile.destroy'), [
                'password' => 'wrong-password',
            ]);

        $response->assertSessionHasErrors('password');
        $response->assertRedirect(route('settings.account'));

        $this->assertNotNull($this->testUser->fresh());
    }
}
