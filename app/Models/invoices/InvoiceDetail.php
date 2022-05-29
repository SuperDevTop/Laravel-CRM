<?php

namespace App\Models\invoices;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model {

	protected $table = 'invoicedetails';
	protected $guarded = array('id');

	public $timestamps = false;

	public function invoice() {
		return $this->hasOne('Invoice', 'id', 'invoiceId');
	}

	public function quoteEntry() {
		return $this->hasOne('QuoteDetail', 'id', 'quoteId');
	}

	public function getDiscountSum() {
		return ((($this->quantity * $this->unitPrice) * ($this->discount / 100)));
	}

	public function getTotal() {
		// Return the price of this entry (quantity * unit price - discount)
		return ((($this->quantity * $this->unitPrice) * (1-($this->discount / 100))));
	}

	public function getVAT() {
		return ($this->getTotal() * (VAT::find($this->invoice->vat)->value) / 100);
	}

	public function getEntrySubTotal() {
		return $this->getTotal() + $this->supCosts + $this->getVAT();
	}

}