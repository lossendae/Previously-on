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

    public function episodes()
    {
        return $this->hasMany('Lossendae\PreviouslyOn\Models\Episode');
    }

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

    public function scopeSeasons($query)
    {
        return $query->select(DB::raw('COUNT(DISTINCT season_number) as seasons'))
                     ->leftJoin('episodes', 'tv_shows.id', '=', 'episodes.tv_show_id')
                     ->where('season_number', '>', 0);
    }

    public function scopeRemaining($query, $id = 0, $returnResult = false)
    {
        $remainingEpisodes = 'COUNT(CASE WHEN ' . DB::getTablePrefix() . 'episodes.viewed = 0 AND ';
        $remainingEpisodes .= DB::getTablePrefix() . 'episodes.season_number > 0 AND ';
        $remainingEpisodes .= DB::getTablePrefix() . 'episodes.first_aired < NOW() THEN 1 END) as remaining';

        $query->leftJoin('episodes', 'tv_shows.id', '=', 'episodes.tv_show_id')
              ->addSelect(DB::raw($remainingEpisodes));

        if($id > 0)
        {
            $query->where('tv_shows.id', '=', $id);
        }

        if($returnResult)
        {
            return $query->first();
        }

        return $query;
    }
}
