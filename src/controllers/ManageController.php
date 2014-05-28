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

use Config;
use Lossendae\PreviouslyOn\Models\TvShow;
use Lossendae\PreviouslyOn\Models\Episode;

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
        $query  = TvShow::select('tv_shows.*')
                        ->assignedTo($this->user->id)
                        ->allWithRemaining($this->user->id);

        $data = [];
        $results = $query->get();

        if(!empty($results))
        {
            foreach($query->get() as $entry)
            {
                if(is_null($entry->id))
                {
                    break;
                }
                $row = $entry->toArray();
                $row['poster']    = Config::get('previously-on::app.assets') . '/images/cache/' . $entry->id . '/poster-thumb.jpg';
                $row['remaining'] = (int) $entry->remaining;
                $row['status']    = $entry->remaining > 0 ? 1 : 0;
                $data[]           = $row;
            }
        }

        \Log::debug('DEBUG', [\DB::getQueryLog()]);

        $response['success'] = true;
        $response['total']   = count($data);
        $response['data']    = $data;

        return $response;
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

        $query  = TvShow::select('episodes.*')
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
     * @param int $id
     * @return array
     */
    public function removeTvShow($id)
    {
        $response = array('success' => false);
        $tvShow   = TvShow::find($id);
        if($tvShow->delete())
        {
            $response['success'] = true;
        }

        return $response;
    }
}
