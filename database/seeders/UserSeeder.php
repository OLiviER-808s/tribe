<?php

namespace Database\Seeders;

use App\Constants\ConstStatus;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private const PASSWORD = 'password';

    public function run(): void
    {
        User::factory()->create([
            'email' => 'tribe.placeholder@tribe.com',
            'name' => 'Tribe User',
            'username' => 'tribe_user',
            'bio' => null,
            'password' => null,
            'location' => null,
            'status' => ConstStatus::USER_INACTIVE,
        ]);

        $users = User::factory()->createMany([
            [
                'email' => 'test1@test.com',
                'name' => 'Test User 1',
                'username' => 'test_user1',
                'password' => Hash::make(self::PASSWORD),
            ],
            [
                'email' => 'test2@test.com',
                'name' => 'Test User 2',
                'username' => 'test_user2',
                'password' => Hash::make(self::PASSWORD),
            ],
            [
                'email' => 'test3@test.com',
                'name' => 'Test User 3',
                'username' => 'test_user3',
                'password' => Hash::make(self::PASSWORD),
            ],
        ]);

        foreach ($users as $user) {
            $user->interests()->saveMany(Topic::inRandomOrder()->take(5)->get());
        }
    }
}
