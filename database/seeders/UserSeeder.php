<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory()->createMany([
            [
                'email' => 'test1@test.com',
                'name' => 'Test User 1',
                'username' => 'test_user1',
            ],
            [
                'email' => 'test2@test.com',
                'name' => 'Test User 2',
                'username' => 'test_user2',
            ],
            [
                'email' => 'test3@test.com',
                'name' => 'Test User 3',
                'username' => 'test_user3',
            ],
        ]);

        foreach ($users as $user) {
            $user->attachTags(Tag::inRandomOrder()->take(5)->pluck('name')->toArray());
        }
    }
}
