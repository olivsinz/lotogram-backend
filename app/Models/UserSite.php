<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSite extends Pivot
{
	protected $casts = [
		'user_id' => 'int',
		'site_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'site_id'
	];

	public function site()
	{
		return $this->belongsTo(Site::class);
	}
}
