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

/**
 * Class Base
 *
 * @package Lossendae\PreviouslyOn\Services
 */
abstract class Base
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    use ResponseFormatter;
    use ValidationResponseFormatter;

    /**
     * @param Container $app
     */
    function __construct(Container $app)
    {
        $this->app    = $app;
        $this->config = $this->app['config'];
    }
}
