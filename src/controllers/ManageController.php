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

/**
 * Class AssignedTvShowController
 *
 * @package Lossendae\PreviouslyOn\Controllers
 */
class AssignedTvShowController extends BaseController
{
    /**
     * @var array
     */
    protected $seasons = array();

    /**
     * Get the list of the TV shows
     *
     * @return array
     */
    public function getList()
    {
        return $this->app['tvshow.service']->getList($this->user);
    }

    /**
     * Get all a show and attached episodes
     *
     * @param int $id the TV Show id
     * @return array
     */
    public function manage($id)
    {
        $result = $this->app['tvshow.service']->getOne($id, $this->user);
        $result = array_merge($result, $this->app['episode.service']->getList($id, $this->user));

        return $result;
    }

    /**
     * This is not an elegant process but hey : ship first, refactor if necessary later
     *
     * @param int $id
     * @return array
     */
    public function remove($id)
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
