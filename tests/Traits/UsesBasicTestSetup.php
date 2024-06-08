<?php

namespace Tests\Traits;

use App\Models\Topic;
use App\Models\User;
use Database\Seeders\TopicCategorySeeder;
use Database\Seeders\TopicSeeder;

trait UsesBasicTestSetup
{
    private User $testUser;

    private User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TopicCategorySeeder::class);
        $this->seed(TopicSeeder::class);

        $this->testUser = User::factory()->create();
        $this->testUser->interests()->saveMany(Topic::inRandomOrder()->take(5)->get());

        $this->otherUser = User::factory()->create();
        $this->otherUser->interests()->saveMany(Topic::inRandomOrder()->take(5)->get());

        $this->be($this->testUser);

        $this->extraSetup();
    }

    protected function extraSetup()
    {
    }
}
