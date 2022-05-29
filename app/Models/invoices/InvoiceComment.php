<?php

namespace App\Models\invoices;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Classes\CommonFunctions;

class InvoiceComment extends Model {

	protected $table = 'invoicecomments';

	public $timestamps = false;

	public function toArray() {
		$array = parent::toArray();
		$array['placedByName'] = $this->placedByName;
		$array['placedByInitials'] = $this->placedByInitials;
		$array['placedOn'] = CommonFunctions::formatDateTime($this->placedOn);
		return $array;
	}
	
	public function getPlacedByNameAttribute() {
		return User::find($this->placedBy)->getFullname();
	}

	public function getPlacedByInitialsAttribute() {
		return User::find($this->placedBy)->initials;
	}

}