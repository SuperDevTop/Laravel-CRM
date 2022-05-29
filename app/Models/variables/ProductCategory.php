<?php 

namespace App\Models\variables;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model {
	protected $table = 'productcategories';

	public function getProducts() {
		return $this->hasMany('Product', 'category', 'id');
	}
}

?>