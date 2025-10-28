<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
	protected $casts = [
		'transaction_id' => 'int',
		'callback_status' => 'bool',
		'source_of_funds' => 'int',
        'method_payload' => 'json'
	];

	protected $fillable = [
		'transaction_id',
		'provider_callback_response',
		'callback_status',
		'source_of_funds',
        'method_payload'
	];

	public function transaction()
	{
		return $this->belongsTo(Transaction::class);
	}
}
