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
use Lossendae\PreviouslyOn\Repositories\Eloquent\TvShowRepository;
use Lossendae\PreviouslyOn\Repositories\Eloquent\EpisodeRepository;
use Lossendae\PreviouslyOn\Services\ApiService;
use Lossendae\PreviouslyOn\Services\TvShowService;
use Lossendae\PreviouslyOn\Services\EpisodeService;

/**
 * Class PreviouslyOnServiceProvider
 *
 * @package Lossendae\PreviouslyOn
 */
class PreviouslyOnServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     *
     */
    public function boot()
    {
        //For PSR-4 compatibility we need to specify the correct path (3rd parameter)
        $this->package('lossendae/previously-on', null, __DIR__);

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

        // add the user seed command to the application
        $this->app['pvon:user'] = $this->app->share(function($app)
        {
            return new Commands\CreateUserCommand($app);
        });

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
        $this->commands('pvon:user');
        $this->commands('pvon:install');
        $this->commands('pvon:update');

        $this->addBindings();
    }

    /**
     * Add classes binding to the IoC container
     */
    protected function addBindings()
    {
        // Add services
        $this->app->bind('tvshow.service', function($app)
        {
            return new TvShowService($app);
        });
        $this->app->bind('episode.service', function($app)
        {
            return new EpisodeService($app);
        });
        $this->app->bind('api.service', function($app)
        {
            return new ApiService($app);
        });

        // Add repositories
        $this->app->bind('tvshow.repository', function($app)
        {
            return new TvShowRepository($app);
        });
        $this->app->bind('episode.repository', function($app)
        {
            return new EpisodeRepository($app);
        });
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
