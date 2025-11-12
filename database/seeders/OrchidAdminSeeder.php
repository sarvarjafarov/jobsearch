<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrchidAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'),
                'permissions' => [
                    'platform.jobs' => true,
                    'platform.posts' => true,
                    'platform.companies' => true,
                    'platform.scraper' => true,
                    'platform.seo' => true,
                    'platform.systems.users' => true,
                    'platform.systems.roles' => true,
                ],
            ],
        );
    }
}
