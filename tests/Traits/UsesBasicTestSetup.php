<?php

namespace Tests\Traits;

use App\Constants\ConstStatus;
use App\Models\User;

trait UsesBasicTestSetup
{
    protected function setUp(): void
    {
        parent::setUp();

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
}