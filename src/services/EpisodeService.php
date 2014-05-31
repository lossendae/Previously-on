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

use Lossendae\PreviouslyOn\Models\User;

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
     * @param $user
     * @return array
     */
    public function getList($id, $user)
    {
        $episodes = $this->app['episode.repository']->listAll($id, $user->id);

        foreach($episodes as $episode)
        {
            $row = $episode->toArray();
            $this->processRow($row);
        }

        return $this->success(array('seasons' => $this->seasons));
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
}
