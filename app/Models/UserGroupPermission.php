<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserGroupPermission extends Model {
	protected $table = 'usergrouppermissions';
	protected $connection = 'mysql2';
	public $timestamps = false;
}