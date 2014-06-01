<?php namespace Lossendae\PreviouslyOn\Services\Validators;

class User extends Base
{
    public static $rules = array(
        'create' => array(
            'username' => array('required', 'min:3', 'max:16', 'unique:users'),
            'email' => array('required', 'email', 'unique:users'),
            'pwd' => array('required', 'min:6', 'max:18'),
        ),
        'update' => array(
            'username' => array('required', 'min:3', 'max:16'),
            'email' => array('required', 'email'),
            'pwd' => array('min:6', 'max:18'),
        ),
        'login' => array(
            'username' => array('required', 'exists:users'),
            'pwd' => array('required', 'min:6', 'max:18'),
        ),
    );
}
