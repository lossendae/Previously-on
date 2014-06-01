<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Lossendae\PreviouslyOn\Models;

use User as Base;

/**
 * Class User
 *
 * @package Lossendae\PreviouslyOn\Models
 */
class User extends Base
{
    /**
     * Defining fillable attributes on the model
     *
     * @var array
     */
    protected $fillable = array(
        'username',
        'password',
        'email',
    );

    /**
     * @return mixed
     */
    public function tvShows()
    {
        return $this->belongsToMany(__NAMESPACE__ . '\\' . 'TvShow', 'assigned_tv_shows')->withPivot('tv_show_id');
    }

    /**
     * @return mixed
     */
    public function episodes()
    {
        return $this->belongsToMany(__NAMESPACE__ . '\\' . 'Episode', 'watched_episodes')->withPivot('episode_id');
    }
}
