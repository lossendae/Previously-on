<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Lossendae\PreviouslyOn\Controllers;

use Controller, Auth;
use Illuminate\Container\Container;

class BaseController extends Controller
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;
    /**
     * @var $user \User;
     */
    protected $user;

    /**
     * @param Container $app
     */
    function __construct(Container $app)
    {
        $this->app = $app;
        $this->user = Auth::user();
    }
}
