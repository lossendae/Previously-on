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
use Lossendae\PreviouslyOn\Services\ApiService;
use Illuminate\Support\Facades\Input;

class ApiController extends Controller
{
    protected $service;

    function __construct(ApiService $service)
    {
        $this->service = $service;
    }

    /**
     * Add a tv show to pool of watched series in the db (tv show + Episodes)
     *
     * @param int $id
     * @return array
     */
    public function put($id)
    {
        return $this->service->assign($id);
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
