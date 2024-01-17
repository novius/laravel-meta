<?php

namespace Novius\LaravelMeta;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Novius\LaravelMeta\Services\ModelHasMetaService;

class LaravelMetaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ModelHasMetaService::class, function () {
            return new ModelHasMetaService();
        });
    }

    public function boot()
    {
        $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/laravel-meta')]);
        $this->publishes([__DIR__.'/../lang' => $this->app->langPath('vendor/laravel-meta')]);

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-meta');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-meta');

        $this->configureMacros();
    }

    protected function configureMacros()
    {

        Blueprint::macro('addMeta', function ($column = 'meta') {
            $this->json($column)->nullable();
        });
    }
}
