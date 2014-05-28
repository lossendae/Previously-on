<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Lossendae\PreviouslyOn\Controllers\Api;

use Controller, Config, Auth;
use FPN\TheTVDB\HttpClient\Buzz;
use FPN\TheTVDB\Api;
use Lossendae\PreviouslyOn\Models\TvShow;

abstract class BaseController extends Controller
{
    protected $httpClient;
    protected $api;
    protected $existingIds = array();
    protected $user;

    public function __construct()
    {
        $this->httpClient = new Buzz();
        $this->api        = new Api($this->httpClient, Config::get('previously-on::app.api_key'));
        $this->user       = Auth::user();
    }

    /**
     * Check whether the current TheTvDBid exists in the database to prevent duplicate entry
     *
     * @param $current
     * @return bool
     */
    protected function checkId($current)
    {
        if(empty($this->existingIds))
        {
            $idsObj = TvShow::select('thetvdb_id')
                            ->assignedTo($this->user->id)
                            ->get();

            foreach($idsObj as $entry)
            {
                $this->existingIds[] = $entry->thetvdb_id;
            }
        }

        return in_array($current, $this->existingIds);
    }
}
