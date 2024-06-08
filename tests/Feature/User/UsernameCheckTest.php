<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\UsesBasicTestSetup;

class UsernameCheckTest extends TestCase
{
    use RefreshDatabase, UsesBasicTestSetup;

    public function test_check_username_returns_true_when_username_is_taken()
    {
        $response = $this->get(route('username.check', [
            'username' => $this->otherUser->username,
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertJson([
                'taken' => true,
            ]);
    }

    public function test_check_username_returns_false_when_username_is_not_taken()
    {
        $response = $this->get(route('username.check', [
            'username' => 'username',
        ]));

        $response
            ->assertSessionHasNoErrors()
            ->assertOk()
            ->assertJson([
                'taken' => false,
            ]);
    }
}
