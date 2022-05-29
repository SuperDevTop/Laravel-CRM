<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ExpensePayment extends Model {

	protected $table = 'expensepayments';

	public function toArray() {
		$array = parent::toArray();

		$array['payMethod'] = $this->getPaymethod->type;

		return $array;
	}

	public function getPaymethod() {
		return $this->hasOne('PayMethod', 'id', 'paymentMethod');
	}

	public function getEntries() {
		return $this->hasMany('ExpensePaymentDetail', 'expensePayment');
	}

}