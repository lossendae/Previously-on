<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Lossendae\PreviouslyOn\Controllers\Api;

use Config, File;
use Lossendae\PreviouslyOn\Models\TvShow;
use Lossendae\PreviouslyOn\Models\Episode;
use PHPThumb;

/**
 * Class AssignController
 *
 * @package Lossendae\PreviouslyOn\Controllers
 */
class AssignController extends BaseController
{
    /**
     * Add a tv show to pool of watched series in the db (tv show + Episodes)
     *
     * @param int $id
     * @return array
     */
    public function put($id)
    {
        $exists = $this->checkId($id);
        if($exists)
        {
            $tvShow = TvShow::with(array(
                'episodes' => function ($query)
                    {
                        $query->where('season_number', '>', 0);
                    }
            ))
            ->where('thetvdb_id', '=', $id)
            ->first();

            /* Attach current user to the new TV Show */
            $this->assignTvShowToUser($tvShow);

            foreach($tvShow->episodes as $episode)
            {
                $this->assignEpisodeToUser($episode);
            }

            return array('success' => true);
        }

        return $this->createTvShow($id);
    }

    /**
     * @param int $id TheTVDb id
     * @return array
     */
    protected function createTvShow($id)
    {
        $result  = $this->api->getTvShowAndEpisodes($id);
        $origine = $result['tvshow'];

        if(is_null($origine))
        {
            return array(
                'success' => false,
                'message' => 'Une erreur est survenue lors de la récupération de la fiche de la série',
            );
        }

        $toSave = array(
            'name'        => $origine->getName(),
            'first_aired' => $origine->getFirstAired(),
            'overview'    => $origine->getOverview(),
            'network'     => $origine->getNetwork(),
            'thetvdb_id'  => $origine->getTheTvDbId(),
            'imdb_id'     => $origine->getImdbId(),
        );

        $tvShow = TvShow::firstOrCreate($toSave);
        $tvShow->save();

        /* Attach current user to the new TV Show */
        $this->assignTvShowToUser($tvShow);

        /* Create local images */
        $this->createImage($tvShow, $origine);

        foreach($result['episodes'] as $episode)
        {
            $this->addEpisode($episode, $tvShow->id);
        }

        return array('success' => true);
    }

    /**
     * Attach an episode to the current user
     *
     * @param object $tvShow
     */
    protected function assignTvShowToUser($tvShow)
    {
        $tvShow->assigned()
                ->attach($this->user->id);
    }

    /**
     * @param object $source
     * @param        $id
     */
    protected function addEpisode($source, $id)
    {

        $toSave = array(
            'name'           => $source->getName(),
            'first_aired'    => $source->getFirstAired(),
            'overview'       => $source->getOverview(),
            'tv_show_id'     => $id,
            'season_id'      => $source->getSeasonId(),
            'season_number'  => $source->getSeasonNumber(),
            'episode_number' => $source->getEpisodeNumber(),
        );

        $episode = Episode::firstOrCreate($toSave);
        $episode->save();
    }

    /**
     * Poster images to local directory
     *
     * @param object $obj
     * @param string $original
     */
    protected function createImage($obj, $original)
    {
        $cacheDir  = Config::get('previously-on::app.assets_path') . '/images/cache/';
        $targetDir = $cacheDir . $obj->id . '/';


        if(!is_dir($cacheDir))
        {
            File::makeDirectory($cacheDir);
        }

        if(!is_dir($targetDir))
        {
            File::makeDirectory($targetDir);
        }

        $name      = 'poster';
        $extension = '.jpg';
        $target    = $targetDir . $name . $extension;

        $check = $this->checkRemoteImageSource($original->getPosterUrl());

        if($check < 1)
        {
            /* Use a fallback poster image when there is no poster from the remote API server */
            copy(Config::get('previously-on::app.assets_path') . '/images/fallback-poster.jpg', $targetThumb = $targetDir . $name . '-thumb' . $extension);
        }
        else
        {
            copy($original->getPosterUrl(), $target);

            /* Resize the image */
            $targetThumb = $targetDir . $name . '-thumb' . $extension;
            $options     = array(
                'jpegQuality' => 90,
            );
            $thumb       = new PHPThumb\GD($target, $options);
            $thumb->resize(225, 330);
            $thumb->save($targetThumb);
        }
    }

    /**
     * Check the poster value to avoid error on PHPthumb the banner url
     *
     * @param $source
     * @return int
     */
    protected function checkRemoteImageSource($source)
    {
        return strlen(str_replace('http://www.thetvdb.com/banners/', '', $source));
    }
}
