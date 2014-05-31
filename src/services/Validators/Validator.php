<?php namespace Lossendae\PreviouslyOn\Services\Validators;
 
abstract class Validator {

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
        $this->attributes = $data ?: \Input::all();
        $this->level = $level;
    }

    /**
     * @return bool
     */
    public function passes()
    {
        $rules = array();
        if($this->level !== null)
        {
            $rules = static::$rules[$this->level];
        }
        else
        {
            $rules = static::$rules;

        }
        
        $messages = array();
        if(is_array(trans('admin::validation')))
        {
            $messages = trans('admin::validation');
        }

        // @todo Don't use Facade
        $validation = \Validator::make($this->attributes, $rules, $messages);

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


