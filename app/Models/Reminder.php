<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Reminder extends Model {

	protected $table = 'reminders';

	public $timestamps = false;

	public function getSentTo() {
		$users = User::whereIn('id', explode(',', $this->sentTo))->get();

		$sentTo = [];

		foreach($users as $user) {
			$sentTo[] = $user->getFullName();
		}

		return $sentTo;
	}

	public function creator() {
		return $this->hasOne('User', 'id', 'createdBy');
	}

}