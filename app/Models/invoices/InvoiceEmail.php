<?php

namespace App\Models\invoices;

use Illuminate\Database\Eloquent\Model;

class InvoiceEmail extends Model {

	protected $table = 'invoiceemails';
	public $timestamps = false;

	public function attachments() {
		return $this->hasMany('InvoiceEmailAttachment', 'emailId', 'id');
	}

}