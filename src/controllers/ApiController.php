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

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Lossendae\PreviouslyOn\Services\ApiService;
use Lossendae\PreviouslyOn\Services\TvShowService;

/**
 * Class ApiController
 *
 * @package Lossendae\PreviouslyOn\Controllers
 */
class ApiController extends Controller
{
    /**
     * @var \Lossendae\PreviouslyOn\Services\ApiService
     */
    protected $service;
    /**
     * @var \Lossendae\PreviouslyOn\Services\TvShowService
     */
    protected $tvShowService;

    /**
     * @param ApiService    $service
     * @param TvShowService $tvShowService
     */
    function __construct(ApiService $service, TvShowService $tvShowService)
    {
        $this->service = $service;
        $this->tvShowService = $tvShowService;
    }

    /**
     * Add a tv show to pool of watched series in the db (tv show + Episodes)
     *
     * @param int $id
     * @return array
     */
    public function put($id)
    {
        return $this->service->assign($id, $this->tvShowService);
    }

    /**
     * Search for a Tv Show to eventually add to the pool)
     *
     * @return array
     */
    public function get()
    {
        $search = Input::get('q');

        return $this->service->search($search);
    }
}
