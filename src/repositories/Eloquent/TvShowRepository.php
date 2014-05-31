<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Lossendae\PreviouslyOn\Repositories\Eloquent;

/**
 * Class TvShowRepository
 *
 * @package Lossendae\PreviouslyOn\Repositories\Eloquent
 */
class TvShowRepository extends EloquentRepository
{
    /**
     * @var string
     */
    protected $modelClassName = 'Lossendae\\PreviouslyOn\\Models\\TvShow';

    /**
     * Get all the tvshows assigned to the specified user with the number of unseen episodes
     *
     * @param $userId
     * @return mixed
     */
    public function listAll($userId)
    {
        $query = $this->model->select('tv_shows.*')
                       ->assignedTo($userId)
                       ->allWithRemaining($userId)
                       ->orderBy('tv_shows.name')
                       ->groupBy('tv_shows.id');

        return $query->get();
    }
} 
