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

use Illuminate\Routing\Controller;
use View, Config;

class IndexPageController extends Controller
{
    public function index()
    {
        return View::make(Config::get('previously-on::app.index'), array(
            'cssPath'      => Config::get('previously-on::app.assets') . '/css/',
            'libsPath'     => Config::get('previously-on::app.assets') . '/js/libs/',
            'appPath'       => Config::get('previously-on::app.assets') . '/js/app/',
            'config'       => array(
                'paths'  => array(
                    'jsLibs' => Config::get('previously-on::app.assets') . '/js/libs/',
                    'app'    => Config::get('previously-on::app.assets') . '/js/app/',
                    'views'  => Config::get('previously-on::app.assets') . '/partials/',
                ),
            ),
            'dependencies' => Config::get('previously-on::dependencies')
        ));
    }

}
