<?php

namespace Lichv\Socialite;

use Illuminate\Support\Facades\Facade;

/**
 * Class Socialite.
 */
class Socialite extends Facade
{
    /**
     * Return the facade accessor.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'Lichv\\Socialite\\SocialiteManager';
    }
}
