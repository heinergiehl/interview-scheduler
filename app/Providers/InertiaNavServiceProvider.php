<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class InertiaNavServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Inertia::share('nav', function () {
            $user = auth()->user();
            if (!$user) {
                return [];   // login / public pages
            }
            // Minimal objects → the front-end decides how to render.
            return match (true) {
                $user->can('applicant') => [
                    'userType' => 'Applicant',
                    'logoRoute' => route('applicant.home'),
                    'main'  => [
                        ['href' => route('applicant.home'),         'title' => 'Dashboard',   'icon' => 'LayoutGrid'],
                    ],
                    'footer' => [
                        ['href' => 'https://docs.myapp.com', 'title' => 'Docs', 'icon' => 'BookOpen'],
                    ],
                ],
                $user->can('employer') => [
                    'userType' => 'Employer',
                    'logoRoute' => route('employer.home'),
                    'main'  => [
                        ['href' => route('employer.home'),            'title' => 'Dashboard',   'icon' => 'LayoutGrid'],
                    ],
                    'footer' => [
                        ['href' => 'https://github.com/laravel/vue-starter-kit', 'title' => 'Github Repo', 'icon' => 'Folder'],
                        ['href' => 'https://laravel.com/docs',                    'title' => 'Documentation', 'icon' => 'BookOpen'],
                    ],
                ],
                default => [],
            };
        });
    }
}
