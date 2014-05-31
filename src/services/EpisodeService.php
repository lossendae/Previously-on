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

/**
 * Class EpisodeService
 *
 * @package Lossendae\PreviouslyOn\Services
 */
class EpisodeService extends Base
{
    /**
     * @var array
     */
    protected $seasons = array();

    /**
     * Get all the episode and watch status
     *
     * @param $id
     * @return array
     */
    public function getList($id)
    {
        $episodes = $this->app['episode.repository']->listAll($id, $this->user->id);

        foreach($episodes as $episode)
        {
            $row = $episode->toArray();
            $this->processRow($row);
        }

        return $this->success(array('seasons' => $this->seasons));
    }

    /**
     * "Present" the output - Should not be there
     *
     * @param array $episode
     */
    protected function processRow($episode)
    {
        $season = $episode['season_number'];
        if($episode['episode_number'] < 10)
        {
            $episode['episode_number'] = str_pad($episode['episode_number'], 2, 0, STR_PAD_LEFT);
        }
        if($episode['season_number'] < 10)
        {
            $episode['season_number'] = str_pad($episode['season_number'], 2, 0, STR_PAD_LEFT);
        }
        $episode['air_date'] = !is_null($episode['first_aired']) ? date('d M Y', strtotime($episode['first_aired'])) : 'TBA';
        $episode['disabled'] = is_null($episode['first_aired']) || strtotime($episode['first_aired']) > strtotime('now') ? 'disabled' : '';

        $episode['status'] = $episode['status'] ? true : false;

        $this->seasons[$season]['episodes'][] = $episode;
    }

    /**
     * Update an episode watch status
     *
     * @param $id
     * @param $status
     * @return array
     */
    public function updateStatus($id, $status)
    {
        $episode = $this->app['episode.repository']->findOrFail($id, array('id', 'first_aired', 'tv_show_id'));

        /* Double check : we don't update the watch status of an un-aired episode */
        if(strtotime($episode->first_aired) > strtotime('now'))
        {
            return $this->failure("Cet épisode n'a pas encore été diffusé");
        }

        /* Use the pivot table */
        if($status)
        {
            $episode->watched()
                    ->attach($this->user->id);
        }
        else
        {
            $episode->watched()
                    ->detach($this->user->id);
        }

        if($episode->save())
        {
            /* Send back the updated remaining number of episode to watch */
            $watchList = $this->app['tvshow.repository']->getOneWithRemaining($episode->tv_show_id, $this->user->id, true);

            return $this->success(array('remaining' => $watchList->remaining));
        }

        return $this->failure("Le status de l'épisode n'a pas pû être mis à jour");
    }

    /**
     * Create episodes attached to a new tv show
     *
     * @param $show
     * @param $fromApi
     */
    public function handleCreate($show, $fromApi)
    {
        foreach($fromApi['episodes'] as $source)
        {
            $toSave = array(
                'name'           => $source->getName(),
                'first_aired'    => $source->getFirstAired(),
                'overview'       => $source->getOverview(),
                'tv_show_id'     => $show->id,
                'season_id'      => $source->getSeasonId(),
                'season_number'  => $source->getSeasonNumber(),
                'episode_number' => $source->getEpisodeNumber(),
            );

            $this->app['episode.repository']->create($toSave);
        }
    }
}
