<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class OrchidJobPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->each(function (User $user) {
            $permissions = collect($user->permissions ?? [])
                ->put('platform.jobs', true)
                ->all();

            $user->forceFill([
                'permissions' => $permissions,
            ])->save();
        });
    }
}
