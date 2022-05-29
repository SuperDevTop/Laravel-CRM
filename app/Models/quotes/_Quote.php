<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Classes\CommonFunctions;

class Quote extends Model {
	protected $table = 'quotes';
	public $timestamps = false;

	public $guarded = array('id');

	public function toArray() {
		$array = parent::toArray();
		$array['total'] = $this->getTotal();
		$array['subtotal'] = $this->getSubtotal();
		$array['customerName'] = $this->getCustomer->getCustomerName();
		$array['statusText'] = JobStatus::find($this->status)->type;
		return $array;
	}

	public function getCustomer() {
		return $this->hasOne('Customer', 'id', 'customer');
	}

	public function getEntries() {
		return $this->hasMany('QuoteDetail', 'quoteId', 'id');
	}

	public function getComments() {
		return $this->hasMany('QuoteComment', 'quoteId', 'id');
	}

	public function getStatus() {
		return $this->hasOne('JobStatus', 'id', 'status');
	}

	public function getAssignedTo() {
		return $this->hasOne('User', 'id', 'assignedTo');
	}

	public function getDescriptionRows() {
		return $this->hasMany('QuoteDescriptionRow', 'quoteId', 'id');
	}

	public function getTotal() {
		$total = 0.0;

		foreach($this->getEntries as $entry) {
			$total += $entry->getTotal();
		}

		$total += $this->getVat();

		$total += $this->supCosts;

		return $total;
	}

	public function getSubtotal() {
		$total = 0.0;

		foreach($this->getEntries as $entry) {
			$total += ($entry['quantity'] * $entry['unitPrice']) * (1-($entry['discount']) / 100);
		}

		return $total;
	}
	
	// Return the amount that is received with payments
	public function getPaid() {
		$details = $this->hasMany('PaymentDetail', 'quoteId', 'id')->get();
		$paid = 0;
		foreach($details as $detail) {
			$paid += $detail->amount;
		}
		return $paid;
	}

	public function getTotalExcludingSupCosts() {
		return $this->getSubTotal() + $this->getVat();
	}
	

	public function getVat() {
		$total = 0.0;

		foreach($this->getEntries as $entry) {
			$total += $entry->getTotal() * ((VAT::getValue($this->vat))/100);
		}

		return $total;
	}

	public function addComment($message) {
		$comment = new QuoteComment;
		$comment->quoteId = $this->id;
		$comment->placedBy = Auth::user()->id;
		$comment->placedOn = CommonFunctions::getMysqlDate();
		$comment->comment = $message;
		$comment->save();
	}

	public function hasBeenInvoiced() {
		return (InvoiceDetail::where('quoteId', '=', $this->id)->count() != 0);
	}

	public function hasBeenInvoicedAndNotVoided() {
		$invoiceDetail = InvoiceDetail::where('quoteId', '=', $this->id)->orderBy('id', 'DESC')->first();
		if (!$invoiceDetail)
			return false;

		return ($invoiceDetail->quantity > 0);
	}

	public function getInvoiceId() {
		$invoiceDetail = InvoiceDetail::where('quoteId', '=', $this->id)->orderBy('id', 'DESC')->first();
		return $invoiceDetail->invoiceId;
	}
}