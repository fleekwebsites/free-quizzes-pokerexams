<?php

namespace App\Providers;

use App\Services\SidebarNavigationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['partials.sidebar', 'subject', 'index'], function ($view) {
            $navigation = app(SidebarNavigationService::class);
            $sidebarSchools = $navigation->schoolsWithCoursesAndExams();

            $view->with('sidebarSchools', $sidebarSchools);
            $view->with('sidebarCourseCount', $navigation->totalCourseCount());
        });
    }
}
