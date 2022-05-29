<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportFeedback extends Model {

	public $timestamps = false;

	protected $table = 'feedback';
	protected $connection = 'master';

}