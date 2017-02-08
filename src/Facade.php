<?php

namespace Lichv\Socialite;

/**
 * Class Socialite.
 */
class Facade extends \Illuminate\Support\Facades\Facade
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

    /**
     * 获取 服务
     *
     * @param string $name
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic($name, $args)
    {
        $app = static::getFacadeRoot();

        if (method_exists($app, $name)) {
            return call_user_func_array([$app, $name], $args);
        }

        return $app->$name;
    }
}
