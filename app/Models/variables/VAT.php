<?php 

namespace App\Models\variables;
use Illuminate\Database\Eloquent\Model;

class VAT extends Model {
	protected $table = 'vat';

	public static function getValue($vatId) {
		// echo "<pre>"; print_r($vatId); echo "</pre>";
		if (!$vatId)
			return 0;
		return 21;
	}
}

?>