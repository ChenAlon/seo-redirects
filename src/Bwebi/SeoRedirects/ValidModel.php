<?php

namespace Bwebi\SeoRedirects;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\Validator;

/*
 * Credit to JeffreyWay/Laravel-Model-Validation package! :)
 */

class ValidModel extends Eloquent
{
    /**
     * Error message bag.
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected static $rules = [];

    /**
     * Custom messages.
     *
     * @var array
     */
    protected static $messages = [];

    /**
     * Validator instance.
     *
     * @var Illuminate\Validation\Validators
     */
    protected $validator;

    public function __construct(array $attributes = [], Validator $validator = null)
    {
        parent::__construct($attributes);

        $this->validator = $validator ?: \App::make('validator');
    }

    /**
     * Listen for save event.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            return $model->validate();
        });
    }

    /**
     * Validates current attributes against rules.
     */
    public function validate()
    {
        $valid = $this->validator->make($this->attributes, static::$rules, static::$messages);

        if ($valid->passes()) {
            return true;
        }

        $this->setErrors($valid->messages());

        return false;
    }

    /**
     * Set error message bag.
     *
     * @var Illuminate\Support\MessageBag
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Retrieve error message bag.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Inverse of wasSaved.
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }
}
