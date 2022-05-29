<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class DirectDebitDetail extends Model {

	protected $table = 'directdebitdetails';
	protected $guarded = array('id');

	public $timestamps = false;

	public function toArray() {
		$array = parent::toArray();
		$array['customerName'] = $this->customerName;
		return $array;
	}

	public function getCustomerNameAttribute() {
		return Customer::find($this->customer)->getCustomerName();
	}

	public function getInvoice() {
		return $this->hasOne('Invoice', 'id', 'invoice');
	}

}