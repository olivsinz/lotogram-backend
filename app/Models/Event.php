<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * 
 * @property int $id
 * @property string $name
 * @property int $type
 * @property string|null $description
 * 
 * @property Collection|Eventable[] $eventables
 *
 * @package App\Models
 */
class Event extends Model
{
	protected $table = 'events';
	public $timestamps = false;

	protected $casts = [
		'type' => 'int'
	];

	protected $fillable = [
		'name',
		'type',
		'description'
	];

	public function eventables()
	{
		return $this->hasMany(Eventable::class);
	}
}
