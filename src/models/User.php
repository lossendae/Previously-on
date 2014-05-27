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
use Eloquent;

class User extends Base
{
    public $timestamps = false;
    /**
     * Defining fillable attributes on the model
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'email',
    ];

    public function tvShows()
    {
        return $this->belongsToMany('TvShow', 'assigned_tv_shows')->withPivot('tv_show_id');
    }
}
