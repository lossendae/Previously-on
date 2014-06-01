<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Lossendae\PreviouslyOn\Services;

use FPN\TheTVDB\Api;
use FPN\TheTVDB\HttpClient\Buzz;
use Illuminate\Container\Container;
use Lossendae\PreviouslyOn\Repositories\EpisodeRepositoryInterface;
use Lossendae\PreviouslyOn\Repositories\TvShowRepositoryInterface;

/**
 * Class ApiService
 *
 * @package Lossendae\PreviouslyOn\Services
 */
class ApiService extends Base
{
    /**
     * @var \FPN\TheTVDB\HttpClient\Buzz
     */
    protected $httpClient;
    /**
     * @var \FPN\TheTVDB\Api
     */
    protected $api;
    /**
     * @var array
     */
    protected $existingIds = array();

    public function __construct(Container $app, EpisodeRepositoryInterface $episodeRepository, TvShowRepositoryInterface $tvShowRepository)
    {
        $this->httpClient    = new Buzz();
        $this->api           = new Api($this->httpClient, $app['config']->get('previously-on::app.api_key'));

        parent::__construct($app, $episodeRepository, $tvShowRepository);
    }

    /**
     * Add a tv show to pool of watched series in the db (tv show + Episodes)
     *
     * @param               $id
     * @param TvShowService $service
     * @return array
     */
    public function assign($id, TvShowService $service)
    {
        if($this->exists($id))
        {
            $service->assign($id);

            return $this->success();
        }

        $newShow = $this->api->getTvShowAndEpisodes($id);

        return $service->create($newShow);
    }

    /**
     * Check whether the passed API id from TheTvDb exists in the database to prevent duplicate entry
     *
     * @param $current
     * @return bool
     */
    protected function exists($current)
    {
        if(empty($this->existingIds))
        {
            $idsObj = $this->tvShowRepository->listField($this->user->id, 'thetvdb_id');

            foreach($idsObj as $entry)
            {
                $this->existingIds[] = $entry->thetvdb_id;
            }
        }

        return in_array($current, $this->existingIds);
    }

    /**
     * Search for a tv show via the API
     *
     * @param $search
     * @return array
     */
    public function search($search)
    {
        /* Most of the time, the result will only be returned on exact match - this is not google! */
        $results = $this->api->searchTvShow($search);

        if(!empty($results))
        {
            $data = array();
            foreach($results as $entry)
            {
                $data[] = array(
                    'id'         => $entry->getTheTvDbId(),
                    'name'       => $entry->getName(),
                    'banner_url' => $entry->getBannerUrl(),
                    'exists'     => $this->exists($entry->getTheTvDbId()),
                );
            }

            return $this->success(array('data' => $data));
        }

        return $this->failure(array());
    }
}
