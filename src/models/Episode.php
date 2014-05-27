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

use DB, Eloquent;

class Episode extends Eloquent
{
    /**
     * Defining fillable attributes on the model
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'overview',
        'first_aired',
        'tv_show_id',
        'season_id',
        'season_number',
        'episode_number',
        'viewed',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function tvShow()
    {
        return $this->belongsTo('Lossendae\PreviouslyOn\Models\tvShow');
    }

    public function watched()
    {
        return $this->belongsToMany('User', 'watched_episodes')->withPivot('user_id', 'status');
    }

    public function scopeWatched($query, $id)
    {
        $result = $query->select(DB::raw('COUNT(CASE WHEN viewed = 1 THEN 1 END) as watched'))
                        ->where('tv_show_id', '=', $id)
                        ->first();

        return $result->watched;
    }

    public function scopeTotal($query, $id)
    {
        $result = $query->select(DB::raw('COUNT(id) as total'))
                        ->where('tv_show_id', '=', $id)
                        ->first();

        return $result->total;
    }
}
