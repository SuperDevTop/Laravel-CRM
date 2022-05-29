<?php 

namespace App\Models\variables;
use Illuminate\Database\Eloquent\Model;

class CustomerCreditRating extends Model {
	protected $table = 'credit';

	public function getCustomers() {
		return $this->hasMany('Customer', 'credit', 'id');
	}
}

?>