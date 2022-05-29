<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditnoteDetail extends Model {

	protected $table = 'creditnotedetails';
	protected $guarded = array('id');

	public $timestamps = false;

	public function getTotal() {
		// Return the price of this entry (quantity * unit price - discount)
		return ((($this->quantity * $this->unitPrice) * (1-($this->discount / 100))));
	}

}