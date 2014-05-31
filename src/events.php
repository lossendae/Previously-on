<?php

Event::listen('previously-on.tv_show_created', 'Lossendae\PreviouslyOn\Services\EpisodeService@handleCreate');
Event::listen('previously-on.tv_show_created', 'Lossendae\PreviouslyOn\Services\Handlers\ThumbnailHandler@handleCreatePoster');
Event::listen('previously-on.tv_show_deleted', 'Lossendae\PreviouslyOn\Services\Handlers\ThumbnailHandler@handleDeletePoster');
