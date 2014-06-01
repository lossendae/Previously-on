<?php namespace Lossendae\PreviouslyOn\Services\Validators;
 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

abstract class Base {

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var null
     */
    protected $level;

    /**
     * @var array
     */
    public static $rules = array();

    /**
     * @param null $data
     * @param null $level
     */
    public function __construct($data = null, $level = null)
    {
        $this->attributes = $data ?: Input::all();
        $this->level = $level;
    }

    /**
     * @return bool
     */
    public function passes()
    {
        if($this->level !== null)
        {
            $rules = static::$rules[$this->level];
        }
        else
        {
            $rules = static::$rules;

        }
        
        $messages = array();
        if(is_array(trans('previously-on::validation')))
        {
            $messages = trans('previously-on::validation');
        }

        $validation = Validator::make($this->attributes, $rules, $messages);

        if($validation->passes())
        {
            return true;
        }

        $this->errors = $validation->messages()->getMessages();

        return false;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

}


