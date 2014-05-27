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

use Controller;
use Lossendae\PreviouslyOn\Models\TvShow;
use Lossendae\PreviouslyOn\Models\Episode;

class EpisodeStatusController extends Controller
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
        $episode->watched()
                ->sync(array(1, array('status' => $status)));

        if($episode->save())
        {
            /* Send back the updated remaining number of episode to watch */
            $watchList = TvShow::remaining($episode->tv_show_id, true);

            $response['remaining'] = $watchList->remaining;
            $response['success']   = true;


        }

        return $response;
    }
}
