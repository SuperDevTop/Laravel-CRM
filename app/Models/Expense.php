<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model {

	protected $table = 'expenses';

	protected $guarded = array('id');

	public function toArray() {
		$array = parent::toArray();

		$array['isOfficial'] = ($this->isOfficial == 1) ? true : false;
		$array['isInternal'] = ($this->isInternal == 1) ? true : false;
		$array['waitingForInvoice'] = ($this->waitingForInvoice == 1) ? true : false;

		return $array;
	}

	public function getSupplier() {
		return $this->hasOne('Supplier', 'id', 'supplier');
	}

	public function getProducts() {
		return $this->hasMany('ExpenseProduct', 'expense', 'id');
	}

	public function getSubtotals() {
		return $this->hasMany('ExpenseSubtotal', 'expense', 'id');
	}

	public function getPayments() {
		return $this->hasMany('ExpensePayment', 'expense', 'id');
	}

	public function getCategory() {
		return $this->hasOne('ExpenseCategory', 'id', 'category');
	}

	public function getSubCategory() {
		return $this->hasOne('ExpenseSubcategory', 'id', 'subcategory');
	}

	public function getPaymentsWithDetails() {
		$payments = $this->getPayments;

		foreach($payments as $payment) {
			$payment['entries'] = $payment->getEntries;
		}

		return $payments;
	}

}