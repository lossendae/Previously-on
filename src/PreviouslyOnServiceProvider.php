<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Lossendae\PreviouslyOn;

use Illuminate\Support\ServiceProvider as ServiceProvider;

class PreviouslyOnServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->package('lossendae/previously-on');
//        $this->registerSession();
//        $this->registerCookie();

        // include start file
        include(__DIR__ . '/start.php');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // load package config
        $this->app['config']->package('lossendae/previously-on', __DIR__.'/config');

        // add the install command to the application
        $this->app['pvon:install'] = $this->app->share(function($app)
        {
            return new Commands\InstallCommand($app);
        });

        // add the update command to the application
        $this->app['pvon:update'] = $this->app->share(function($app)
        {
            return new Commands\UpdateCommand($app);
        });

        // add commands
        $this->commands('pvon:install');
        $this->commands('pvon:update');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
