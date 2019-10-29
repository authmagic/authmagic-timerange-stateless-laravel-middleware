<?php

namespace Authmagic\AuthmagicLaravel;

use Illuminate\Support\Arr;
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
                Arr::get($config, 'url'),
                Arr::get($config, 'cache_duration')
            );
        });
    }
}
