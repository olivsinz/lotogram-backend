<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StringRule implements ValidationRule
{
    protected $pattern;
    protected $message;

    public function __construct($pattern, $message = null)
    {
        $this->pattern = $pattern;
        $this->message = $message ?? trans('validation.string');
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match($this->pattern, $value)) {
            $fail($this->message);
        }
    }

    public static function username()
    {
        return new self('/^[A-Za-z][A-Za-z0-9._]+$/');
    }

    public static function firstName()
    {
        return new self('/^[A-Za-z]+$/');
    }

    public static function defaults()
    {
        return new self('/^[A-Za-z0-9çÇğĞıİöÖşŞüÜ.,\[\]()=@\-\_+*\s]+$/');
    }

    public static function lastName()
    {
        return new self('/^[A-Za-z]+$/');
    }

    public static function address()
    {
        return new self('/^[A-Za-z0-9\s,.-]+$/');
    }

    public static function phone()
    {
        return new self('/^\+90(5[0-9]{2})[0-9]{7}$/');
    }

    public static function nationalId()
    {
        return new self('/^\d+$/');
    }

}
