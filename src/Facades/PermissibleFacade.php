<?php

namespace Shahnewaz\Permissible\Facades;

use Illuminate\Support\Facades\Facade;

class PermissibleFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'permissible';
    }
}
