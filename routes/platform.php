<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Job;
use App\Orchid\Screens\Company\CompanyEditScreen;
use App\Orchid\Screens\Company\CompanyListScreen;
use App\Orchid\Screens\Company\CompanyReviewListScreen;
use App\Orchid\Screens\Job\JobEditScreen;
use App\Orchid\Screens\Job\JobListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Jobs
Route::screen('jobs', JobListScreen::class)
    ->name('platform.jobs.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Jobs', route('platform.jobs.list')));

Route::screen('jobs/create', JobEditScreen::class)
    ->name('platform.jobs.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.jobs.list')
        ->push('Create job', route('platform.jobs.create')));

Route::screen('jobs/{job}/edit', JobEditScreen::class)
    ->name('platform.jobs.edit')
    ->breadcrumbs(fn (Trail $trail, Job $job) => $trail
        ->parent('platform.jobs.list')
        ->push($job->position, route('platform.jobs.edit', $job)));

// Companies
Route::screen('companies', CompanyListScreen::class)
    ->name('platform.companies.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Companies', route('platform.companies.list')));

Route::screen('companies/create', CompanyEditScreen::class)
    ->name('platform.companies.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.companies.list')
        ->push('Create company', route('platform.companies.create')));

Route::screen('companies/{company}/edit', CompanyEditScreen::class)
    ->name('platform.companies.edit')
    ->breadcrumbs(fn (Trail $trail, Company $company) => $trail
        ->parent('platform.companies.list')
        ->push($company->name, route('platform.companies.edit', $company)));

Route::screen('company-reviews', CompanyReviewListScreen::class)
    ->name('platform.company.reviews')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Company Reviews', route('platform.company.reviews')));

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Route::screen('idea', Idea::class, 'platform.screens.idea');
