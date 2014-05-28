<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Lossendae\PreviouslyOn\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pvon:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the TV Show manager';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('## Installation... ##');

        // publish conponent config
        $this->call('config:publish', array('package' => 'lossendae/previously-on' ) );

        // publish component assets
        $this->call('asset:publish', array('package' => 'lossendae/previously-on' ) );

        // run migrations
        $this->call('migrate', array('--env' => $this->option('env'), '--package' => 'previously-on' ) );
    }
} 
