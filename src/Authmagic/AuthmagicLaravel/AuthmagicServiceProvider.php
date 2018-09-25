<?php

namespace Authmagic\AuthmagicLaravel;

use Illuminate\Support\ServiceProvider;

class AuthmagicServiceProvider extends ServiceProvider
{
    public static $alias = 'authmagic';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the configuration file
        $this->publishes(array(
            __DIR__ . '/../../config.php' => config_path(static::$alias . '.php'),
        ), 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(static::$alias, function ($app) {
            $config = $app->config->get(static::$alias);

            return new Authmagic(
                array_get($config, 'url'),
                array_get($config, 'cache_duration')
            );
        });
    }
}
