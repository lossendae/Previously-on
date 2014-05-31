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

use Config, DB;
use Lossendae\PreviouslyOn\Models\TvShow;
use Lossendae\PreviouslyOn\Models\Episode;
use Lossendae\PreviouslyOn\Models\User;

class ManageController extends BaseController
{
    protected $seasons = array();

    /**
     * Get the list of the TV shows cached in the db
     *
     * @return array
     */
    public function query()
    {
        return $this->app['tvshow.service']->getList($this->user);
    }

    /**
     * Retrieve all a show and all its episodes
     *
     * @param int $id the TV Show id
     * @return array
     */
    public function listSeasons($id)
    {
        $data = $row = [];

        $query  = TvShow::select('tv_shows.*')
                        ->oneWithRemaining($id, $this->user->id);
        $result = $query->first();

        $data['serie'] = $result->toArray();

        $episodes = Episode::select('episodes.*')
                           ->withStatus($id, $this->user->id)
                           ->get();

        foreach($episodes as $episode)
        {
            $row = $episode->toArray();
            $this->processRow($row);
        }
        $data['seasons'] = $this->seasons;

        $response['success'] = true;
        $response['total']   = TvShow::count();
        $response['data']    = $data;

        return $response;
    }

    /**
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
     * This is not an elegant process but hey : ship first, refactor if necessary later
     *
     * @param int $id
     * @return array
     */
    public function removeTvShow($id)
    {
        $response = array('success' => false);
        $tvShow   = TvShow::find($id);

        if(!is_object($tvShow))
        {
            return array_merge($response, array('message' => 'Error while trying to delete the TV show'));
        }

        User::find($this->user->id)
            ->tvShows()
            ->detach($id);

        /* Delete the Tv show association to the user */
        $episodes = Episode::select('id')
                           ->where('tv_show_id', '=', $id);

        /* Same process for the episodes */
        $ids = array();
        foreach($episodes->get() as $episode)
        {
            $ids[] = $episode->id;
        }

        DB::table('watched_episodes')
          ->whereIn('episode_id', $ids)
          ->where('user_id', '=', $this->user->id)
          ->delete();

        /* Let's check if the tv show is still attached to another user - if not delete */
        $total = DB::table('assigned_tv_shows')
                   ->where('tv_show_id', '=', $id)
                   ->count();

        /* The model will handle cascading trough the episodes and image poster */
        if($total == 0)
        {
            $tvShow->delete();
        }


        return array('success' => true);
    }
}
