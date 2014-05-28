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

use Controller, Auth;
use Lossendae\PreviouslyOn\Models\TvShow;
use Lossendae\PreviouslyOn\Models\Episode;

class EpisodeStatusController extends BaseController
{
    /**
     * @param $id
     * @param $status
     * @return array
     */
    public function update($id, $status)
    {
        $response = ['success' => false];

        $episode = Episode::find($id);

        /* Double check : we don't update the watch status of an un-aired episode */
        if(strtotime($episode->first_aired) > strtotime('now'))
        {
            return $response;
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
            $watchList = TvShow::oneWithRemaining($episode->tv_show_id, $this->user->id, true);

            $response['remaining'] = $watchList->remaining;
            $response['success']   = true;
        }

        return $response;
    }
}
