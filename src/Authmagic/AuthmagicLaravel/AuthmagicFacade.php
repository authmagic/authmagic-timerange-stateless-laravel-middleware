<?php

namespace AuthMagic\AuthmagicLaravel;

use Illuminate\Support\Facades\Facade;

class AuthmagicFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'authmagic';
    }
}
