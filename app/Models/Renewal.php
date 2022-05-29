<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Renewal extends Model {

	protected $table = 'renewals';
	protected $guarded = array('id');

	public $timestamps = false;

	public function getProduct() {
		return $this->hasOne('Product', 'id', 'product');
	}

	public function getCustomer() {
		return $this->hasOne('Customer', 'id', 'customer');
	}

	public function getNextRenewalDateAttribute($value) {
		return date('d-m-Y', strtotime($value));
	}

	public function getStartDateAttribute($value) {
		return date('d-m-Y', strtotime($value));
	}

}