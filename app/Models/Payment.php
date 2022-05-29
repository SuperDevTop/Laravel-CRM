<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

	protected $table = 'payments';
	protected $guarded = array('id');

	public $timestamps = false;

	public function getCustomer() {
		return $this->hasOne('Customer', 'id', 'customerId');
	}

	public function getPayMethod() {
		return $this->hasOne('PayMethod', 'id', 'paymentType');
	}

	public function getCreatedBy() {
		return $this->hasOne('User', 'id', 'createdBy');
	}

	public function getTotal() {
		$total = 0;

		$total += $this->nonCash;

		$total += $this->n500 * 500;
		$total += $this->n200 * 200;
		$total += $this->n100 * 100;
		$total += $this->n50 * 50;
		$total += $this->n20 * 20;
		$total += $this->n10 * 10;
		$total += $this->n5 * 5;

		$total += $this->c200 * 2;
		$total += $this->c100 * 1;
		$total += $this->c50 * 0.50;
		$total += $this->c20 * 0.20;
		$total += $this->c10 * 0.10;
		$total += $this->c5 * 0.05;
		$total += $this->c2 * 0.02;
		$total += $this->c1 * 0.01;

		return $total;
	}

}