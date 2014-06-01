<?php
/*
* This file is part of the Lossendae\PreviouslyOn.
*
* (c) Stephane Boulard <lossendae@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Lossendae\PreviouslyOn\Controllers;

use Illuminate\Routing\Controller;
use Lossendae\PreviouslyOn\Helpers\ResponseFormatter;
use Lossendae\PreviouslyOn\Services\Validators\User as UserValidator;
use Input, Auth;

class AuthController extends Controller
{
    use ResponseFormatter;

    /**
     * @return array
     */
    public function login()
    {
        $validator = new UserValidator(Input::all(), 'login');

        if(!$validator->passes())
        {
            return array('logged' => false, 'messages' => $this->formatErrors($validator->getErrors()));
        }

        $credentials = array(
            'username'    => Input::get('username'),
            'password' => Input::get('pwd'),
        );

        if (!Auth::attempt($credentials))
        {
            return array('logged' => false, 'message' => "Nom d'utilisateur ou mot de passe invalide");
        }

        return array('logged' => true, 'user' => Auth::user()->toArray());
    }

    /**
     * @return array
     */
    public function logout()
    {
        Auth::logout();

        return array('logged' => false);
    }
}
