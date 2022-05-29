<?php

namespace App\Models;
use App\Models\DirectDebitDetail;
use Illuminate\Database\Eloquent\Model;

class DirectDebitJob extends Model {

	protected $table = 'directdebitjobs';
	protected $guarded = array('id');

	public $timestamps = false;

	public function getTotal() {
		return DirectDebitDetail::where('job', '=', $this->id)->sum('total');
	}

	public function getInvoices() {
		return DirectDebitDetail::where('job', '=', $this->id)->get();
	}
}