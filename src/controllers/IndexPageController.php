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

use BaseController;

class IndexPageController extends BaseController
{
    public function index()
    {
        return View::make('home', array(
            'cssPath'      => 'css/',
            'libsPath'     => 'js/app/libs/',
            'jsPath'       => 'js/',
            'config'       => array(
                'paths'  => array(
                    'jsLibs' => '/js/app/libs/',
                    'app'    => '/js/app/',
                    'views'  => '/partials/',
                ),
            ),
            'dependencies' => Config::get('previously.deps')
        ));
    }

}
