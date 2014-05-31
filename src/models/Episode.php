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

use DB;
use Eloquent;

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
        return $this->belongsTo(__NAMESPACE__ . '\\' . 'tvShow');
    }

    public function watched()
    {
        return $this->belongsToMany(__NAMESPACE__ . '\\' . 'User', 'watched_episodes')
                    ->withPivot('user_id');
    }

    public function scopeWithStatus($query, $showId, $userId)
    {
        $query->addSelect(DB::raw('IF(' . DB::getTablePrefix() . 'watched_episodes.user_id, 1, 0) AS status'))
              ->leftJoin('watched_episodes', function ($join) use ($userId)
              {
                  $join->on('episodes.id', '=', 'watched_episodes.episode_id')
                       ->where('watched_episodes.user_id', '=', (int) $userId);
              })
              ->where('episodes.tv_show_id', '=', (int) $showId)
              ->where('episodes.season_number', '>', (int) 0)
              ->orderBy('episodes.season_number', 'asc')
              ->orderBy('episodes.episode_number', 'asc');

        return $query;
    }

    public function scopeTotal($query, $id)
    {
        $result = $query->select(DB::raw('COUNT(id) as total'))
                        ->where('tv_show_id', '=', $id)
                        ->first();

        return $result->total;
    }
}
