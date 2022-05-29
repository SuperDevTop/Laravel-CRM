<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VATConfirm extends Model {

	protected $table = 'vatconfirms';

	public function getUser() {
		return $this->hasOne('User', 'id', 'user');
	}

}