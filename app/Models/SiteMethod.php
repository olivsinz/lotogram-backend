<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMethod extends Model
{
	protected $casts = [
		'site_id' => 'int',
		'method_id' => 'int',
		'deposit_commission' => 'int',
		'withdraw_commission' => 'int',
		'has_redirect' => 'int',
		'redirect_mode' => 'int'
	];

	protected $fillable = [
		'site_id',
		'method_id',
		'deposit_commission',
		'withdraw_commission',
		'has_redirect',
		'redirect_url',
		'redirect_mode'
	];

	public function site()
	{
		return $this->belongsTo(Site::class);
	}

    public function method()
    {
        return $this->belongsTo(Method::class);
    }
}
