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
 * Class EpisodeRepository
 *
 * @package Lossendae\PreviouslyOn\Repositories\Eloquent
 */
class EpisodeRepository extends EloquentRepository
{
    /**
     * @var string
     */
    protected $modelClassName = 'Lossendae\\PreviouslyOn\\Models\\Episode';

    /**
     * Get all the episodes with the watched status
     *
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function listAll($id, $userId)
    {
        $query = $this->model->select('episodes.*')
                        ->withStatus($id, $userId);

        return $query->get();
    }
} 
