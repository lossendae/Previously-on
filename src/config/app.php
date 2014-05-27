<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

return array(
    'api_key' => '',

    /* The url used as landing page for the application */
    'uri' => '/',

    /* Path to package in public */
    'assets' => '/packages/lossendae/previously-on',
    'assets_path' => public_path() . '/packages/lossendae/previously-on',

    /* The landing page view */
    'index' => 'previously-on::home',
);
