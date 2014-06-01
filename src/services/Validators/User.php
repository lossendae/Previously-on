<?php namespace Lossendae\PreviouslyOn\Services\Validators;

class User extends Base
{
    public static $rules = array(
        'create' => array(
            'email' => array('required', 'email', 'unique:users'),
            'pwd' => array('required', 'min:6', 'max:18'),
            'username' => array('required', 'min:3', 'max:16', 'unique:users'),
        ),
        'update' => array(
            'email' => array('required', 'email'),
            'pwd' => array('min:6', 'max:18'),
            'username' => array('required', 'min:3', 'max:16'),
        ),
        'login' => array(
            'username' => array('required', 'exists:users'),
            'pwd' => array('required', 'min:6', 'max:18'),
        ),
    );
}
