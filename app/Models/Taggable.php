<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Taggable
 * 
 * @property int $id
 * @property int $tag_id
 * @property int $taggable_id
 * @property string $taggable_type
 *
 * @package App\Models
 */
class Taggable extends Model
{
	protected $table = 'taggables';
	public $timestamps = false;

	protected $casts = [
		'tag_id' => 'int',
		'taggable_id' => 'int'
	];

	protected $fillable = [
		'tag_id',
		'taggable_id',
		'taggable_type'
	];
}
