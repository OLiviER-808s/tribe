<?php

namespace Tests\Traits;

use App\Models\AdminUser;
use Database\Seeders\TopicCategorySeeder;
use Database\Seeders\TopicSeeder;

trait UsesNovaTestSetup
{
    private AdminUser $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TopicCategorySeeder::class);
        $this->seed(TopicSeeder::class);

        $this->adminUser = AdminUser::factory()->create();
        $this->actingAs($this->adminUser, 'admin_user');

        $this->extraSetup();
    }

    protected function extraSetup() {}
}
