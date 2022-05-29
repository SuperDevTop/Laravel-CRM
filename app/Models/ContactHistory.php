<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ContactHistory extends Model {
	protected $table = 'contacthistory';
	public $timestamps = false;

	public function getPlacedBy() {
		return $this->hasOne(User::class, 'id', 'placedBy');
	}
}