<?php
/**
 * Created by PhpStorm.
 * User: Boulard
 * Date: 31/05/14
 * Time: 15:57
 */

namespace Lossendae\PreviouslyOn\Repositories;

interface EpisodeRepositoryInterface
{
    /**
     * Get all the episodes with the watched status
     *
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function listAll($id, $userId);

    /**
     * Delete all the watch status(es) for a tv show related to a specified user
     *
     * @param $id
     * @param $userId
     */
    public function deleteEpisodesFor($id, $userId);
} 
