<?php

namespace App\Models;
use App\Models\Currency;
use App\Models\SupplierComment;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {

	protected $table = 'suppliers';
	protected $guarded = array('id');

	public function getContactHistory() {
		return $this->hasMany(SupplierComment::class, 'supplier', 'id');
	}

	public function getDisplayName() {
		return (!empty($this->tradingName) ? ($this->companyName . ' (' . $this->tradingName . ')') : $this->companyName);
	}

	public function getPaymentType() {
		return $this->hasOne('PayMethod', 'id', 'paymentType');
	}

	public function getPaymentTerms() {
		return $this->hasOne('PaymentTerm', 'id', 'paymentTerms');
	}

	public function getCurrency() {
		return $this->hasOne(Currency::class, 'id', 'currency');
	}
	
}