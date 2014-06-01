<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Lossendae\PreviouslyOn\Services;

use Illuminate\Container\Container;
use Lossendae\PreviouslyOn\Helpers\ResponseFormatter;
use Lossendae\PreviouslyOn\Helpers\ValidationResponseFormatter;
use Lossendae\PreviouslyOn\Repositories\EpisodeRepositoryInterface;
use Lossendae\PreviouslyOn\Repositories\TvShowRepositoryInterface;

/**
 * Class Base
 *
 * @package Lossendae\PreviouslyOn\Services
 */
abstract class Base
{
    protected $config;

    protected $user;

    /**
     * @var \Lossendae\PreviouslyOn\Repositories\TvShowRepositoryInterface
     */
    protected $tvShowRepository;

    /**
     * @var \Lossendae\PreviouslyOn\Repositories\EpisodeRepositoryInterface
     */
    protected $episodeRepository;

    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    use ResponseFormatter;
    use ValidationResponseFormatter;

    /**
     * @param Container                  $app
     * @param EpisodeRepositoryInterface $episodeRepository
     * @param TvShowRepositoryInterface  $tvShowRepository
     */
    function __construct(Container $app, EpisodeRepositoryInterface $episodeRepository, TvShowRepositoryInterface $tvShowRepository)
    {
        $this->app               = $app;
        $this->config            = $app['config'];
        $this->user              = $this->app['auth']->user();
        $this->episodeRepository = $episodeRepository;
        $this->tvShowRepository  = $tvShowRepository;
    }
}
