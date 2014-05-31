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
 * Class TvShowService
 *
 * @package Lossendae\PreviouslyOn\Services
 */
class TvShowService extends Base
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * Get a list of tv show for the specified user
     *
     * @return array
     */
    public function getList()
    {
        $results = $this->app['tvshow.repository']->listAll($this->user->id);

        if(!empty($results))
        {
            foreach($results as $entry)
            {
                if(is_null($entry->id))
                {
                    break;
                }
                $row              = $entry->toArray();
                $row['poster']    = $this->config->get('previously-on::app.assets') . '/images/cache/' . $entry->id . '/poster-thumb.jpg';
                $row['remaining'] = (int)$entry->remaining;
                $row['status']    = $entry->remaining > 0 ? 1 : 0;
                $data[]           = $row;
            }
        }

        return $this->success(array(
            'total' => count($data),
            'data'  => $data,
        ));
    }

    /**
     * Get a single Tv show and the remaining number of unseen episode(s)
     *
     * @param $id
     * @return array
     */
    public function getOne($id)
    {
        $result = $this->app['tvshow.repository']->getOne($id, $this->user->id);

        return $this->success(array('tv_show' => $result->toArray()));
    }

    /**
     * This is not an elegant process but hey : ship first, refactor if necessary later
     *
     * @param int $id
     * @return array
     */
    public function remove($id)
    {
        $tvShowRepository  =& $this->app['tvshow.repository'];
        $episodeRepository =& $this->app['episode.repository'];

        $tvShow = $tvShowRepository->find($id);

        if(!is_object($tvShow))
        {
            return $this->failure(array('message' => 'Error while trying to delete the TV show'));
        }

        /* Delete the related episodes association */
        $episodeRepository->deleteEpisodesFor($id, $this->user->id);

        /* Detach the tv show from the current logged user */
        $this->user->tvShows()
                   ->detach($id);

        /* Let's check if the tv show is still attached to another user */
        $assigned = $tvShowRepository->getTotalAssigned($id);

        /* The model will handle cascading trough the episodes and image poster */
        if((int)$assigned == 0)
        {
            $tvShow->delete();
        }

        return $this->success();
    }
}
