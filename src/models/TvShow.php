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

use Config, File, DB;
use Eloquent;

class TvShow extends Eloquent
{
    /**
     * Defining fillable attributes on the model
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'overview',
        'network',
        'first_aired',
        'banner_url',
        'poster_url',
        'fanart_url',
        'thetvdb_id',
        'imdb_id',
        'zap2it_id',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($tvShow)
        {
            // Remove all the related episodes
            $tvShow->episodes()
                   ->delete();

            // Remove the associated poster image
            $cacheDir = Config::get('previously-on::app.assets_path') . '/images/cache/' . $tvShow->id;
            File::deleteDirectory($cacheDir);
        });
    }

    public function episodes()
    {
        return $this->hasMany(__NAMESPACE__ . '\\' . 'Episode');
    }

    public function assigned()
    {
        return $this->belongsToMany(__NAMESPACE__ . '\\' . 'User', 'assigned_tv_shows')
                    ->withPivot('user_id');
    }

    public function scopeSeasons($query)
    {
        return $query->select(DB::raw('COUNT(DISTINCT season_number) as seasons'))
                     ->leftJoin('episodes', 'tv_shows.id', '=', 'episodes.tv_show_id')
                     ->where('season_number', '>', 0);
    }

    public function scopeAssignedTo($query, $userId)
    {
        $query->join('assigned_tv_shows', function ($join) use ($userId)
              {
                  $join->on('tv_shows.id', '=', 'assigned_tv_shows.tv_show_id')
                       ->where('assigned_tv_shows.user_id', '=', (int)$userId);
              });

        return $query;
    }

    public function scopeAllWithRemaining($query, $userId)
    {
        $remainingEpisodes = 'COUNT(CASE WHEN IF(' . DB::getTablePrefix() . 'watched_episodes.user_id, 1, 0) = 0 AND ';
        $remainingEpisodes .= DB::getTablePrefix() . 'episodes.first_aired < NOW() THEN 1 END) as remaining';

        $query->addSelect(DB::raw($remainingEpisodes))
              ->leftJoin('episodes', 'tv_shows.id', '=', 'episodes.tv_show_id')
              ->leftJoin('watched_episodes', function ($join) use ($userId)
              {
                  $join->on('episodes.id', '=', 'watched_episodes.episode_id')
                       ->where('watched_episodes.user_id', '=', (int)$userId);
              })
              ->where('episodes.season_number', '>', (int)0);

        return $query;
    }

    public function scopeOneWithRemaining($query, $id = 0, $userId, $returnResult = false)
    {
        $query->allWithRemaining($userId, $returnResult)
              ->where('tv_shows.id', '=', $id);

        if($returnResult)
        {
            return $query->first();
        }

        return $query;
    }
}
