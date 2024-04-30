<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Tags\Tag;

class UserSeeder extends Seeder
{
    private const PASSWORD = 'password1';

    public function run(): void
    {
        $users = User::factory()->createMany([
            [
                'email' => 'test1@test.com',
                'name' => 'Test User 1',
                'username' => 'test_user1',
                'password' => Hash::make(self::PASSWORD)
            ],
            [
                'email' => 'test2@test.com',
                'name' => 'Test User 2',
                'username' => 'test_user2',
                'password' => Hash::make(self::PASSWORD)
            ],
            [
                'email' => 'test3@test.com',
                'name' => 'Test User 3',
                'username' => 'test_user3',
                'password' => Hash::make(self::PASSWORD)
            ],
        ]);

        foreach ($users as $user) {
            $user->attachTags(Tag::inRandomOrder()->take(5)->pluck('name')->toArray());
        }
    }
}
