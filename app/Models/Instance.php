<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instance extends Model {

	protected $connection = 'master';
	protected $table = 'instances';

}