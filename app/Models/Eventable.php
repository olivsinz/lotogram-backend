<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eventable extends Model
{
	protected $table = 'eventable';
	public $timestamps = false;

	protected $casts = [
		'event_id' => 'int',
		'ownerable_id' => 'int',
		'expired_at' => 'datetime'
	];

	protected $fillable = [
		'event_id',
		'ownerable_id',
		'ownerable_type',
		'description',
		'expired_at'
	];

	public function event()
	{
		return $this->belongsTo(Event::class);
	}
}
