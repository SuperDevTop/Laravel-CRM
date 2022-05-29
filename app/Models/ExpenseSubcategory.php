<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ExpenseSubCategory extends Model {

	protected $table = 'expensesubcategories';

	public function getCategory() {
		return $this->hasOne('ExpenseCategory');
	}

}