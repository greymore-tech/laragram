<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Get the instance of the alias loader.
        $loader = AliasLoader::getInstance();

        // Register our custom facade alias.
        $loader->alias('MadelineProto', \App\Facades\MadelineProto::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
