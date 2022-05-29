<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportQuestion extends Model {

	public $timestamps = false;
	protected $connection = 'master';
	protected $table = 'questions';

}