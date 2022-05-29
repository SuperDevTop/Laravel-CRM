<?php

namespace App\Models\quotes;
use Illuminate\Database\Eloquent\Model;
use App\Classes\CommonFunctions;

class QuoteDetail extends Model {
	protected $table = 'quotedetails';
	public $timestamps = false;

	protected $guarded = array('id');

	public function product() {
		return $this->hasOne('Product', 'id', 'productId');
	}

	public function toArray() {
		$array = parent::toArray();
		$array['visitDate'] = CommonFunctions::formatDate($this->visitDate);
		return $array;
	}

	public function getTotal() {
		// Return the price of this entry ((quantity * unit price) - discount))
		return ($this->quantity * $this->unitPrice) * (1-($this->discount / 100));
	}
}