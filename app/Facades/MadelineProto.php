<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MadelineProto extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'madeline-proto';
    }
}
