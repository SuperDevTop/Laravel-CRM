<?php
namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Eloquent\Model;
use app\Classes\CommonFunctions;

// use SoftDeletingTrait;
class QuoteComment extends Model {
	protected $table = 'quotecomments';
	public $timestamps = false;

	public $guarded = array('id');


	
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