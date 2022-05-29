<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model {

	protected $table = 'paymentdetails';
	protected $guarded = array('id');

	public $timestamps = false;

	public function getPayment() {
		return $this->hasOne('Payment', 'id', 'paymentId');
	}

}