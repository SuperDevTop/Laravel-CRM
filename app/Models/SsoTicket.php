<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SsoTicket extends Model {

	protected $connection = 'master';
	protected $table = 'sso_tickets';
	public $timestamps = false;

}