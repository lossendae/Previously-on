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

use Input;

class SearchController extends BaseController
{
    /**
     * Search for a Tv Show to eventually add to the pool
     *
     * @return array
     */
    public function get()
    {
        $response = ['data' => []];
        $search    = Input::get('q');

        /* Most of the time, the result will only be returned on exact match - this is not google! */
        $results = $this->api->searchTvShow($search);

        if(!empty($results))
        {
            foreach($results as $entry)
            {
                $response['data'][] = [
                    'id'         => $entry->getTheTvDbId(),
                    'name'       => $entry->getName(),
                    'banner_url' => $entry->getBannerUrl(),
                    'exists'     => false,
//                    'exists'     => $this->checkId($entry->getTheTvDbId()),
                ];
            }
            $response['success'] = true;
        }
        else
        {
            $response['success'] = false;
        }

        return $response;
    }
}
