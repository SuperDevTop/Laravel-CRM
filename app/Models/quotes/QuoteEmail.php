<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Classes\CommonFunctions;

class QuoteEmail extends Model {

	protected $table = 'quoteemails';
	public $timestamps = false;

	public function attachments() {
		return $this->hasMany('QuoteEmailAttachment', 'emailId', 'id');
	}

	public function toArray() {
		$array = parent::toArray();
		$array['sentOn'] = CommonFunctions::formatDateTime($this->sentOn);
		return $array;
	}

}