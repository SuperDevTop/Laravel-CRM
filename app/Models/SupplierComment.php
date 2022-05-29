<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierComment extends Model {

	protected $table = 'suppliercomments';
	protected $guarded = array('id');

	public $timestamps = false;

}