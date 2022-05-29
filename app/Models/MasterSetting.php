<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSetting extends Model {

	public $timestamps = false;
	protected $table = 'configuration';

	protected $connection = 'master';

}