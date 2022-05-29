<?php 

namespace App\Models\variables;
use Illuminate\Database\Eloquent\Model;

class CompanyRole extends Model {
	protected $table = 'companyroles';

	public function getUsers() {
		return $this->hasMany('User', 'companyRole', 'id');
	}
}