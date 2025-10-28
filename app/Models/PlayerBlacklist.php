<?php

namespace App\Models;

use App\Traits\Model\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PlayerBlacklist extends Model
{
    use HasUuid;

	protected $casts = [
		'id' => 'int',
		'method_id' => 'int',
		'national_id' => 'int',
		'wallet_no' => 'int',
		'phone' => 'int',
		'site_id' => 'int',
		'purpose' => 'int'
	];

	protected $fillable = [
		'uuid',
        'method_id',
		'national_id',
		'wallet_no',
		'phone',
		'site_id',
		'description',
		'purpose'
	];

	public function site()
	{
		return $this->belongsTo(Site::class);
	}
}
