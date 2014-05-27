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
use Symfony\Component\Console\Input\InputArgument;
use Lossendae\PreviouslyOn\Services\Validators\User as UserValidator;
use Lossendae\PreviouslyOn\Models\User;
use Hash;

class CreateUserCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pvon:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the a user for this app';

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
        try
        {
            $username = $this->argument('username');
            $pass     = $this->argument('password');
            $email    = $this->argument('email');

            $validator = new UserValidator(array(
                'username' => $username,
                'pwd'      => $pass,
                'email'    => $email,
            ), 'create');

            if(!$validator->passes())
            {
                foreach($validator->getErrors() as $key => $messages)
                {
                    $this->info(ucfirst($key) . ' :');
                    foreach($messages as $message)
                    {
                        $this->error($message);
                    }
                }
            }
            else
            {
                // Create the user
//                $password = Hash::make('secret');

                User::create([
                    'username' => $username,
                    'password' => Hash::make($pass),
                    'email'    => $email,
                ]);

                $this->info('User created');
            }
        }
        catch(\Exception $e)
        {
            $this->error('User already exists ! + ' . $e->getMessage());
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('username', InputArgument::REQUIRED, 'User name'),
            array('password', InputArgument::REQUIRED, 'User password'),
            array('email', InputArgument::REQUIRED, 'User email'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }
}
