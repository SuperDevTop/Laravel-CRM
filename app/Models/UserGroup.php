<?php

use App\Models\User;
use App\Models\UserGroupPermission;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model {
	protected $table = 'usergroups';
	public $timestamps = false;
	protected $connection = 'mysql2';

	public function getPermissions() {
		return UserGroupPermission::where('groupId', '=', $this->id)->pluck('permission');
	}

	public function getMemberCount() {
		return User::where('userGroup', '=', $this->id)->count();
	}
}