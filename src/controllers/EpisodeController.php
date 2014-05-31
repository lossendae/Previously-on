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

/**
 * Class EpisodeController
 *
 * @package Lossendae\PreviouslyOn\Controllers
 */
class EpisodeController extends BaseController
{
    /**
     * Update an episode status
     *
     * @param $id
     * @param $status
     * @return array
     */
    public function update($id, $status)
    {
        return $this->app['episode.service']->updateStatus($id, $status);
    }
}
