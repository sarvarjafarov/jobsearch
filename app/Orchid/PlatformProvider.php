<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Dashboard')
                ->icon('bs.speedometer')
                ->title('Navigation')
                ->route(config('platform.index')),

            Menu::make('Jobs')
                ->icon('bs.briefcase')
                ->route('platform.jobs.list')
                ->permission('platform.jobs')
                ->title('Content'),

            Menu::make('Companies')
                ->icon('bs.building')
                ->route('platform.companies.list')
                ->permission('platform.companies'),

            Menu::make('Company Reviews')
                ->icon('bs.chat-dots')
                ->route('platform.company.reviews')
                ->permission('platform.companies'),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group('Jobs')
                ->addPermission('platform.jobs', 'Manage job listings'),
            ItemPermission::group('Companies')
                ->addPermission('platform.companies', 'Manage companies'),

            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
