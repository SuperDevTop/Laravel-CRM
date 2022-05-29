<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model {

	protected $table = 'error_log';
	protected $connection = 'master';
	public $timestamps = false;

}