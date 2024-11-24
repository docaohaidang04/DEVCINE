<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new Account();
        $user->user_name = 'User 1';
        $user->password = Hash::make('123456');
        $user->email = 'user1@example.com';
        $user->full_name = 'User One';
        $user->phone = '123456789';
        $user->role = 'user';
        $user->loyalty_points = 0;
        $user->refresh_token = null;
        $user->save();
    }
}
