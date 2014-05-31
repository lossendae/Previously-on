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
 * Class TvShowController
 *
 * @package Lossendae\PreviouslyOn\Controllers
 */
class TvShowController extends BaseController
{
    /**
     * @var array
     */
    protected $seasons = array();

    /**
     * Get the list of the TV shows
     *
     * @return array
     */
    public function getList()
    {
        return $this->app['tvshow.service']->getList();
    }

    /**
     * Get all a show and attached episodes
     *
     * @param int $id the TV Show id
     * @return array
     */
    public function manage($id)
    {
        $result = $this->app['tvshow.service']->getOne($id);
        $result = array_merge($result, $this->app['episode.service']->getList($id));

        return $result;
    }

    /**
     * This is not an elegant process but hey : ship first, refactor if necessary later
     *
     * @param int $id
     * @return array
     */
    public function remove($id)
    {
        return $this->app['tvshow.service']->remove($id);
    }
}
