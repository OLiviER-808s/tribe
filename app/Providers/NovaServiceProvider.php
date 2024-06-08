<?php

namespace App\Providers;

use App\Models\AdminUser;
use App\Nova\AdminUser as AdminUserResource;
use App\Nova\Lenses\TopicsToReview;
use App\Nova\Topic;
use App\Nova\TopicCategory;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::mainMenu(function () {
            return [
                MenuSection::make('Dashboard')->path('/dashboards/main')->icon('chart-bar'),
                MenuSection::make('Users')->path('/resources/users')->icon('user'),
                MenuSection::make('Admin', [
                    MenuItem::lens(Topic::class, TopicsToReview::class),
                ])->collapsedByDefault(),
                MenuSection::make('Config', [
                    MenuItem::resource(Topic::class),
                    MenuItem::resource(TopicCategory::class),
                    MenuItem::resource(AdminUserResource::class),
                ])->collapsedByDefault()->icon('cog'),
            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user instanceof AdminUser;
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
