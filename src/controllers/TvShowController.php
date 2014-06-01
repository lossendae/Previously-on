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
use Lossendae\PreviouslyOn\Services\EpisodeService;
use Lossendae\PreviouslyOn\Services\TvShowService;

/**
 * Class TvShowController
 *
 * @package Lossendae\PreviouslyOn\Controllers
 */
class TvShowController extends Controller
{
    protected $service;

    /**
     * @var array
     */
    protected $seasons = array();

    function __construct(TvShowService $service, EpisodeService $episodeService)
    {
        $this->service = $service;
        $this->episodeService = $episodeService;
    }

    /**
     * Get the list of the TV shows
     *
     * @return array
     */
    public function getList()
    {
        return $this->service->getList();
    }

    /**
     * Get all a show and attached episodes
     *
     * @param int $id the TV Show id
     * @return array
     */
    public function manage($id)
    {
        $result = $this->service->getOne($id);
        $result = array_merge($result, $this->episodeService->getList($id));

        return $result;
    }

    /**
     * Update an episode status
     *
     * @param $id
     * @param $status
     * @return array
     */
    public function update($id, $status)
    {
        return $this->episodeService->updateStatus($id, $status);
    }

    /**
     * This is not an elegant process but hey : ship first, refactor if necessary later
     *
     * @param int $id
     * @return array
     */
    public function remove($id)
    {
        return $this->service->remove($id);
    }
}
