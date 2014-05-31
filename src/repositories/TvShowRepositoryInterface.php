<?php
/**
 * Created by PhpStorm.
 * User: Boulard
 * Date: 31/05/14
 * Time: 15:57
 */

namespace Lossendae\PreviouslyOn\Repositories;

/**
 * Interface TvShowRepositoryInterface
 *
 * @package Lossendae\PreviouslyOn\Repositories
 */
interface TvShowRepositoryInterface
{

    /**
     *  Assign the tv show to the specified user
     *
     * @param      $userId
     * @param null $theTvDbId
     * @return mixed
     */
    public function assign($userId, $theTvDbId);

    /**
     * Get all the tvshows assigned to the specified user with the number of unseen episodes
     *
     * @param $userId
     * @return mixed
     */
    public function listAll($userId);

    /**
     * Get one Tv Show by the specified tv show id and user id
     *
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function getOne($id, $userId);

    /**
     * Get one Tv Show by the specified tv show id and user id
     *
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function getOneWithRemaining($id, $userId);

    /**
     * Count the number of user assigned to a tv show
     *
     * @param $id
     * @return mixed
     */
    public function getTotalAssigned($id);
} 
