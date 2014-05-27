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
    'app' => array(
        'ngResource',
        'ngAnimate',

        // Inline
        'commons',

        // 3rd party
        'ui.router',
        'routeResolver',
        'chieffancypants.loadingBar',
        'loadingSpinner',
    ),
    'files' => array(
        'app',
        'config',
        'start',

        // Modules
        'modules/route-resolver',
    ),
);
