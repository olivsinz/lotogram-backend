<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MethodFakeBrowser
 * 
 * @property int $id
 * @property int $method_id
 * @property string $name
 * @property bool $is_active
 * 
 * @property Method $method
 *
 * @package App\Models
 */
class MethodFakeBrowser extends Model
{
	protected $table = 'method_fake_browsers';
	public $timestamps = false;

	protected $casts = [
		'method_id' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'method_id',
		'name',
		'is_active'
	];

	public function method()
	{
		return $this->belongsTo(Method::class);
	}
}
