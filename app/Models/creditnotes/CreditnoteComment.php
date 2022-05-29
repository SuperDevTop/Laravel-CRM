<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Classes\CommonFunctions;

class CreditnoteComment extends Model {

	protected $table = 'creditnotecomments';

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