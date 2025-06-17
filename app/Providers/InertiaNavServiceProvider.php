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
                    'logoRoute' => route('landing-page'),
                    'main'  => [
                        ['href' => route('applicant.home'),         'title' => 'Dashboard',   'icon' => 'LayoutGrid'],
                    ],
                    'footer' => [
                        ['href' => 'https://github.com/heinergiehl/', 'title' => "Heiner's GitHub", 'icon' => 'Github'],
                        ['href' => 'https://heinerdevelops.tech/',                    'title' => "Heiner's Website", 'icon' => 'BookImage'],
                    ],
                ],
                $user->can('employer') => [
                    'userType' => 'Employer',
                    'logoRoute' => route('landing-page'),
                    'main'  => [
                        ['href' => route('employer.home'),            'title' => 'Dashboard',   'icon' => 'LayoutGrid'],
                    ],
                    'footer' => [
                        ['href' => 'https://github.com/heinergiehl/', 'title' => "Heiner's GitHub", 'icon' => 'Github'],
                        ['href' => 'https://heinerdevelops.tech/',                    'title' => "Heiner's Website", 'icon' => 'BookImage'],
                    ],
                ],
                default => [],
            };
        });
    }
}
