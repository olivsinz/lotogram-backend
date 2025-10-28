<?php

namespace App\Traits\FormRequest;

use Illuminate\Support\Facades\Route;

trait MergeParams
{
    public function validationData()
    {
        return array_merge($this->all(), Route::current()->parameters());
    }
}
