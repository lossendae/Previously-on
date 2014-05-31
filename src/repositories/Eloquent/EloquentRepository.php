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

use Illuminate\Container\Container;

/**
 * Class EloquentRepository
 *
 * @package Lossendae\PreviouslyOn\Repositories\Eloquent
 */
abstract class EloquentRepository
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * @var the model to use for query
     */
    protected $model;

    /**
     * @var string The namespaced model name specified in child classes
     */
    protected $modelClassName;

    /**
     * @param Container $app
     */
    function __construct(Container $app)
    {
        $this->app   = $app;
        $this->model = $app[$this->modelClassName];
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->all($columns);
    }

    /**
     * @param       $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param       $id
     * @param array $columns
     * @return mixed
     */
    public function findOrFail($id, $columns = array('*'))
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        return $this->model->delete();
    }
} 
