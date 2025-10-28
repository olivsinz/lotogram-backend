<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserInterfaceSettingRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            $fail(__('validation.custom.settings.json_string_invalid'));
            return;
        }

        if(!$this->checkArray($value)) {
            $fail(__('validation.custom.settings.invalid'));
            return;
        }
    }

    protected function checkArray($array)
    {
        foreach ($array as $key => $val) {
            if (is_array($val))
            {
                if (!$this->checkArray($val))
                    return false;
            }
            else
            {
                if ($val === false)
                    $val = 'false';

                if (!preg_match('/^[A-Za-z0-9 .,\-_+#]+$/', $val))
                    return false;
            }
        }

        return true;
    }
}
