<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseProduct extends Model {

	protected $table = 'expenseproducts';

	public $timestamps = false;

	public function toArray() {
		$array = parent::toArray();

		$array['isAsset'] = (($this->isAsset == 1) ? true : false);

		return $array;
	}

}