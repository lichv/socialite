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

    public static function __callStatic($name, $args)
    {
        $app = static::getFacadeRoot();

        if (method_exists($app, $name)) {
            return call_user_func_array([$app, $name], $args);
        }

        return $app->$name;
    }
}
