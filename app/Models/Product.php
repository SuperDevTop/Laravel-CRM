<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
	protected $table = 'products';
	protected $guarded = array('id');
	function getCategory() {
		return $this->hasOne('ProductCategory', 'id', 'category');
	}

	function getSupplier() {
		return $this->hasOne('ProductCategory', 'id', 'supplier');
	}
}

?>