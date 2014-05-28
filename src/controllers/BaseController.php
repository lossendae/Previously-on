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

class BaseController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
}
