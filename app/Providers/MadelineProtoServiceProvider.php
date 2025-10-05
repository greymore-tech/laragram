<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MadelineProtoService;

class MadelineProtoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('madeline-proto', function ($app) {
            return new MadelineProtoService();
        });
    }

    public function boot(): void
    {
        //
    }
}
