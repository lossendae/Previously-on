<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Lossendae\PreviouslyOn\Services\Handlers;

use PHPThumb\GD as PHPThumb;
use Illuminate\Container\Container;

/**
 * Class ThumbnailHandler
 *
 * @package Lossendae\PreviouslyOn\Services\Handlers
 */
class ThumbnailHandler
{
    /**
     * @var
     */
    protected $config;
    /**
     * @var
     */
    protected $fileSystem;

    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * @param Container $app
     */
    function __construct(Container $app)
    {
        $this->app        = $app;
        $this->config     = $this->app['config'];
        $this->fileSystem = $this->app['files'];
    }

    /**
     * Cache the poster image locally on tv show creation
     *
     * @param object $show
     * @param array  $fromApi
     */
    public function handleCreatePoster($show, $fromApi)
    {
        $cacheDir  = $this->config->get('previously-on::app.assets_path') . '/images/cache/';
        $targetDir = $cacheDir . $show->id . '/';
        $origine   = $fromApi['tvshow'];

        if(!is_dir($cacheDir))
        {
            $this->fileSystem->makeDirectory($cacheDir);
        }

        if(!is_dir($targetDir))
        {
            $this->fileSystem->makeDirectory($targetDir);
        }

        $name      = 'poster';
        $extension = '.jpg';
        $target    = $targetDir . $name . $extension;

        $check = $this->checkRemoteImageSource($origine->getPosterUrl());

        if($check < 1)
        {
            /* Use a fallback poster image when there is no poster from the remote API server */
            copy($this->config->get('previously-on::app.assets_path') . '/images/fallback-poster.jpg', $targetThumb = $targetDir . $name . '-thumb' . $extension);
        }
        else
        {
            copy($origine->getPosterUrl(), $target);

            /* Resize the image */
            $targetThumb = $targetDir . $name . '-thumb' . $extension;
            $options     = array(
                'jpegQuality' => 90,
            );

            $thumb = new PHPThumb($target, $options);
            $thumb->resize(225, 330);
            $thumb->save($targetThumb);
        }
    }

    /**
     * Check the poster value to avoid error on poster cache
     *
     * @param $source
     * @return int
     */
    protected function checkRemoteImageSource($source)
    {
        return strlen(str_replace('http://www.thetvdb.com/banners/', '', $source));
    }

    /**
     * Delete the cache directory for the poster on tv show deletion
     *
     * @param int $id
     */
    public function handleDeletePoster($id)
    {
        $cacheDir = $this->config->get('previously-on::app.assets_path') . '/images/cache/' . $id;
        $this->fileSystem->deleteDirectory($cacheDir);
    }
}
