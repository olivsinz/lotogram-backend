<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserMethod extends Pivot
{
	public $timestamps = false;
    public $parentTable = 'User';

	protected $casts = [
		'user_id' => 'int',
		'method_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'method_id'
	];
}
