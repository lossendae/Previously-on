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

use BaseController, View, Config;

class IndexPageController extends BaseController
{
    public function index()
    {
        // @todo refactor to something more readable / maintenable / clean
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
