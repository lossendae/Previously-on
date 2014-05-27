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

        if(strtotime($episode->first_aired) > strtotime('now'))
        {
            return $response;
        }
        $episode->viewed = $status;

        if($episode->save()) {
            $watchList = TvShow::remaining($episode->tv_show_id, true);
            $response['remaining'] = $watchList->remaining;
            $response['success'] = true;

//        Log::debug('QRY DEBUG PREVIOUSLY.IO', [DB::getQueryLog()]);
        }

        return $response;
    }
}
