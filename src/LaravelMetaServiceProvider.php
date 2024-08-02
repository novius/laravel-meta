<?php

namespace Novius\LaravelMeta;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Novius\LaravelMeta\Services\CurrentModelService;

class LaravelMetaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CurrentModelService::class, function () {
            return new CurrentModelService;
        });
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/laravel-meta')]);
        $this->publishes([__DIR__.'/../lang' => $this->app->langPath('vendor/laravel-meta')]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-meta');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-meta');

        $this->configureMacros();
        $this->configureComponents();
    }

    protected function configureMacros(): void
    {

        Blueprint::macro('addMeta', function ($column = 'meta') {
            $this->json($column)->nullable();
        });
    }

    protected function configureComponents(): void
    {
        Blade::component('laravel-meta::components.description', 'meta-description');
        Blade::component('laravel-meta::components.keywords', 'meta-keywords');
        Blade::component('laravel-meta::components.og_description', 'meta-og-description');
        Blade::component('laravel-meta::components.og_image', 'meta-og-image');
        Blade::component('laravel-meta::components.og_title', 'meta-og-title');
        Blade::component('laravel-meta::components.og_site_name', 'meta-og-site-name');
        Blade::component('laravel-meta::components.og_type', 'meta-og-type');
        Blade::component('laravel-meta::components.og_url', 'meta-og-url');
        Blade::component('laravel-meta::components.og_locale', 'meta-og-locale');
        Blade::component('laravel-meta::components.robots', 'meta-robots');
        Blade::component('laravel-meta::components.title', 'meta-title');
        Blade::component('laravel-meta::components.x_card', 'meta-x-card');
    }
}
