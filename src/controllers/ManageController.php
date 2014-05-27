<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Lossendae\PreviouslyOn;

use Controller;
use FPN\TheTVDB\HttpClient\Buzz;
use FPN\TheTVDB\Api;

class ManageController extends Controller
{
    protected $seasons = array();

    /**
     * Get the list of the TV shows cached in the db
     *
     * @return array
     */
    public function query()
    {
        $remainingEpisodes = 'COUNT(CASE WHEN ' . DB::getTablePrefix() . 'episodes.viewed = 0 AND ';
        $remainingEpisodes .= DB::getTablePrefix() . 'episodes.first_aired < NOW() THEN 1 END) as remaining';

        $series = TvShow::select('tv_shows.*')
                        ->remaining()
                        ->groupBy('tv_shows.id')
                        ->orderBy('tv_shows.name')
                        ->get();

        $data = [];
        foreach($series as $serie)
        {
            $row              = $serie->toArray();
            $row['poster']    = '/images/cache/' . $serie->id . '/poster-thumb.jpg';
            $row['remaining'] = (int)$serie->remaining;
            $row['status']    = (int)$serie->remaining > 0 ? 1 : 0;
            $data[]           = $row;
        }

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

        $serie         = TvShow::select('tv_shows.*')
                               ->remaining($id)
                               ->first();
        $data['serie'] = $serie->toArray();

        $episodes = Episode::where('tv_show_id', $id)
                           ->where('season_number', '>', 0)
                           ->orderBy('season_number', 'asc')
                           ->orderBy('episode_number', 'asc')
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

        $episode['viewed'] = $episode['viewed'] ? true : false;

        $this->seasons[$season]['episodes'][] = $episode;
    }

    /**
     * @param int $id
     * @return array
     */
    protected function removeTvShow($id)
    {
        $response = ['success' => false];
        $tvShow   = TvShow::find($id);
        if($tvShow->delete())
        {
            $response['success'] = true;
        }

        return $response;
    }
}
