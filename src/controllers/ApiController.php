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

use Controller, Config, Input, File;
use FPN\TheTVDB\HttpClient\Buzz;
use FPN\TheTVDB\Api;
use Lossendae\PreviouslyOn\Models\TvShow;
use Lossendae\PreviouslyOn\Models\Episode;
use PHPThumb;

class ApiController extends Controller
{
    protected $httpClient;
    protected $api;
    protected $existingIds = array();

    public function __construct()
    {
        $this->httpClient = new Buzz();
        $this->api        = new Api($this->httpClient, Config::get('previously-on::app.api_key'));
    }

    /**
     * Check whether the current tvdbid exists in the database to prevent duplicate entry
     *
     * @param $current
     * @return bool
     */
    protected function checkId($current)
    {
        if(empty($this->existingIds))
        {
            $idsObj = TvShow::distinct('thetvdb_id')
                            ->get();
            foreach($idsObj as $entry)
            {
                $this->existingIds[] = $entry->thetvdb_id;
            }
        }

        return in_array($current, $this->existingIds);
    }

    /**
     * Search for a Tv Show to eventually add to the pool
     *
     * @return array
     */
    public function search()
    {
        $response = ['data' => []];
        $query    = Input::get('q');

        /* Most of the time, the result will only be returned on exact match - this is not google! */
        $results = $this->api->searchTvShow($query);

        if(!empty($results))
        {
            foreach($results as $entry)
            {
                $response['data'][] = [
                    'id'         => $entry->getTheTvDbId(),
                    'name'       => $entry->getName(),
                    'banner_url' => $entry->getBannerUrl(),
                    'exists'     => $this->checkId($entry->getTheTvDbId()),
                ];
            }
            $response['success'] = true;
        }
        else
        {
            $response['success'] = false;
        }

        return $response;
    }

    /**
     * Add a tv show to pool of watched series in the db (tv show + Episodes)
     *
     * @param int $id
     * @return array
     */
    public function put($id)
    {
        /* Don't ever believe peoples */
        $exists = $this->checkId($id);
        if($exists)
        {
            return [
                'success' => false,
                'message' => 'Cette série est déjà dans votre liste',
            ];
        }

        $result = $this->api->getTvShowAndEpisodes($id);

        $origine = $result['tvshow'];

        if(is_null($origine))
        {
            return [
                'success' => false,
                'message' => 'Une erreur est survenue lors de la récupération de la fiche de la série',
            ];
        }

        $toSave = [
            'name'        => $origine->getName(),
            'first_aired' => $origine->getFirstAired(),
            'overview'    => $origine->getOverview(),
            'network'     => $origine->getNetwork(),
            'thetvdb_id'  => $origine->getTheTvDbId(),
            'imdb_id'     => $origine->getImdbId(),
        ];

        $tvShow = TvShow::firstOrCreate($toSave);
        $tvShow->save();

        /* Create local images */
        $this->createImage($tvShow, $origine);

        $this->addEpisodes($result['episodes'], $tvShow->id);

        return ['success' => true];
    }

    protected function addEpisodes($episodes, $id)
    {
        foreach($episodes as $episode)
        {
            $toSave = [
                'name'           => $episode->getName(),
                'first_aired'    => $episode->getFirstAired(),
                'overview'       => $episode->getOverview(),
                'tv_show_id'     => $id,
                'season_id'      => $episode->getSeasonId(),
                'season_number'  => $episode->getSeasonNumber(),
                'episode_number' => $episode->getEpisodeNumber(),
            ];
            $new    = Episode::firstOrCreate($toSave);
            $new->save();
        }
    }

    /**
     * Poster images to local directory
     *
     * @param object $obj
     * @param string $original
     */
    protected function createImage($obj, $original)
    {
        $cacheDir = Config::get('previously-on::app.assets_path') . '/images/cache/';
        $targetDir = $cacheDir . $obj->id .'/';

        if(!is_dir($cacheDir))
        {
            File::makeDirectory($cacheDir);
        }

        // @todo - do something actually useful with those checking
        if(!is_dir($targetDir))
        {
            File::makeDirectory($targetDir);
        }

        $name      = 'poster';
        $extension = '.jpg';
        $target    = $targetDir . $name . $extension;

        copy($original->getPosterUrl(), $target);

        /* Resize the image */
        $targetThumb = $targetDir . $name . '-thumb' . $extension;
        $options     = array(
            'jpegQuality' => 90,
        );
        $thumb       = new PHPThumb\GD($target, $options);
        $thumb->resize(210, 310);
        $thumb->save($targetThumb);
    }

    /**
     * Get the proper image url for the db
     *
     * @param object $show
     * @param string $which
     * @return string
     */
    protected function getImageUrl($show, $which = 'banner')
    {
        $url = '/images/';
        $url .= str_replace(' ', '', snake_case($show->getName(), '-')) . '-' . $which . '-' . $show->getTheTvDbId() . '.jpg';

        return $url;
    }
}
