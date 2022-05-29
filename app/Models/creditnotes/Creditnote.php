<?php

namespace App\Models\creditnotes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Classes\CommonFunctions;

class Creditnote extends Model {

	protected $table = 'creditnotes';
	protected $guarded = array('id');

	public $timestamps = false;

	public function toArray() {
		$array = parent::toArray();
		$array['total'] = $this->getTotal();
		$array['customerName'] = $this->customername;
		return $array;
	}

	public function getCustomer() {
		return $this->hasOne('Customer', 'id', 'customer');
	}

	public function getComments() {
		return $this->hasMany('CreditnoteComment', 'creditnoteId', 'id');
	}

	public function getEntries() {
		return $this->hasMany('CreditnoteDetail', 'creditnoteId', 'id');
	}

	public function quoteEntries() {
		return Quote::whereIn('id', $this->getEntries->'quoteId'))->get();
	}

	public function getCreatedOnAttribute($value) {
		return date('l d-m-Y H:i', strtotime($value));
	}

	public function getCustomernameAttribute() {
		return Customer::find($this->customer)->getCustomerName();
	}

	public function getTotalAttribute() {
		return $this->getTotal();
	}

	public function getTotal() {
		return $this->getSubtotal() + $this->getVat() + $this->getSupCosts();
	}

	public function getTotalExcludingSupCosts() {
		return $this->getSubtotal() + $this->getVat();
	}

	public function getSupCosts() {
		$supCosts = 0;

		foreach($this->getEntries as $entry) {
			$supCosts += $entry->supCosts;
		}

		return $supCosts;
	}

	public function getSubtotal() {
		$total = 0.0;

		foreach($this->getEntries as $entry) {
			$total += ($entry['quantity'] * $entry['unitPrice']) * (1-($entry['discount'] / 100));
		}

		return $total;
	}

	public function getVat() {
		$total = 0.0;

		foreach($this->getEntries as $entry) {
			$total += ($entry->getTotal()) * (VAT::getValue($this->vat)/100);
		}

		return $total;
	}

	// Return the subtotal (including discount) of the credit note that is work (isWork=1)
	public function getSubWork() {
		$sub = 0;
		// Loop through entries
		foreach($this->getEntries as $entry) {
			$quote = Quote::find($entry->quoteId);
			if ($quote) {
				foreach($quote->getEntries as $quoteEntry) {
					// Fetch product
					$product = Product::find($quoteEntry->productId);
					if ($product) {
						if ($product->isWork == 1)
							$sub += (float) ($entry->quantity * $quoteEntry->getTotal()) * (1-($entry->discount / 100));
					}
				}
			}
		}
		return -$sub;
	}

	// Return the subtotal (including discount) of the credit note that is NOT work (isWork=0)
	public function getSubNotWork() {
		$sub = 0;
		// Loop through entries
		foreach($this->getEntries as $entry) {
			$quote = Quote::find($entry->quoteId);
			if ($quote) {
				foreach($quote->getEntries as $quoteEntry) {
					// Fetch product
					$product = Product::find($quoteEntry->productId);
					if ($product) {
						if ($product->isWork == 0)
							$sub += (float) ($entry->quantity * $quoteEntry->getTotal()) * (1-($entry->discount / 100));
					}
				}
			}
		}
		return -$sub;
	}

	// Insert a new comment
	public function addComment($message) {
		$comment = new CreditnoteComment;
		$comment->creditnoteId = $this->id;
		$comment->placedBy = Auth::user()->id;
		$comment->placedOn = CommonFunctions::getMysqlDate();
		$comment->comment = $message;
		$comment->save();
	}

}