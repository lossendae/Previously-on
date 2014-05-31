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

use Lossendae\PreviouslyOn\Repositories\TvShowRepositoryInterface;

/**
 * Class TvShowRepository
 *
 * @package Lossendae\PreviouslyOn\Repositories\Eloquent
 */
class TvShowRepository extends EloquentRepository implements TvShowRepositoryInterface
{
    /**
     * @var string
     */
    protected $modelClassName = 'Lossendae\\PreviouslyOn\\Models\\TvShow';

    /**
     *  Assign the tv show to the specified user
     *
     * @param      $userId
     * @param null $theTvDbId
     * @return mixed
     */
    public function assign($userId, $theTvDbId)
    {

        $show = $this->model->where('thetvdb_id', '=', $theTvDbId)
                            ->first();

        $show->assigned()
             ->attach($userId);
    }

    /**
     * Get all the tvshows assigned to the specified user with the number of unseen episodes
     *
     * @param $userId
     * @return mixed
     */
    public function listAll($userId)
    {
        return $this->model->select('tv_shows.*')
                           ->assignedTo($userId)
                           ->allWithRemaining($userId)
                           ->orderBy('tv_shows.name')
                           ->groupBy('tv_shows.id')
                           ->get();
    }

    /**
     * Get all the specified field from tvshows assigned to the specified user
     *
     * @param        $userId
     * @param string $field
     * @return mixed
     */
    public function listField($userId, $field = 'id')
    {
        return $this->model->select($field)
                           ->assignedTo($userId)
                           ->get();
    }

    /**
     * Get one Tv Show by the specified tv show id and user id
     *
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function getOne($id, $userId)
    {
        return $this->model->select('tv_shows.*')
                           ->oneWithRemaining($id, $userId)
                           ->first();
    }

    /**
     * Get one Tv Show by the specified tv show id and user id
     *
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function getOneWithRemaining($id, $userId)
    {
        return $this->model->oneWithRemaining($id, $userId, true);
    }

    /**
     * Count the number of user assigned to a tv show
     *
     * @param $id
     * @return mixed
     */
    public function getTotalAssigned($id)
    {
        return $this->app['db']->table('assigned_tv_shows')
                               ->where('tv_show_id', '=', $id)
                               ->count();
    }
} 
