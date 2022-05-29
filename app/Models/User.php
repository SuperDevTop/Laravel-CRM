<?php

namespace App\Models;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\variables\CompanyRole;
use App\Models\Reminder;

class User extends Authenticatable{
	// class User extends Eloquent implements UserInterface, RemindableInterface {

	protected $table = 'users';
	// protected $connection = 'mysql2';
	
	public function getCompanyRole() {
		return $this->hasOne(CompanyRole::class, 'id', 'companyRole');
	}

	// Guard id, password and perms from mass assignment
	protected $guarded = array('id', 'password');

	public function getReminders() {
		return Reminder::where('user', '=', $this->id);
	}

	public function getActiveReminders() {
		return $this->getReminders()->where('dismissed', '=', 0)->orderBy('reminderDate', 'ASC');
	}

	public function getActiveReminderCount() {
		return $this->getReminders()->where('dismissed', '=', 0)->count();
	}

	public function getUnreadReminders() {
		return $this->getReminders()->where('reminderDate', '<=', date('Y-m-d H:i:s'))->where('read', '=', 0)->orderBy('reminderDate', 'ASC')->get();
	}

	public function getUnreadReminderCount() {	
		return $this->getReminders()->where('reminderDate', '<=', date('Y-m-d H:i:s'))->where('read', '=', 0)->count();
	}

	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getAuthPassword()
	{
		return $this->password;
	}

	public function getReminderEmail()
	{
		return $this->email;
	}

	// Get a user's full name (firstname + lastname)
	public function getFullname() {
		return $this->firstname . ' ' . $this->lastname;
	}

	public function getFullNameAttribute() {
		return $this->attributes['firstname'] . ' ' . $this->attributes['lastname'];
	}

	public function getUserGroup() {
		return $this->hasOne(UserGroup::class, 'id', 'userGroup');
	}

	public function getPermissions() {
		return $this->getUserGroup->getPermissions();
	}

	public function hasPermission($perm) {

		return true;

		/*if (array_key_exists('userPermissions', $_ENV) && is_array($_ENV['userPermissions']) && count($_ENV['userPermissions']) > 0) {
			return ((in_array($perm, $_ENV['userPermissions'])) || in_array('*', $_ENV['userPermissions']));
		} else {
			return (in_array($perm, $this->getPermissions()) || in_array('*', $this->getPermissions()));
		}*/
	}

	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}

	public static function getActiveUsers() {
		return User::where('disabled', '=', 0)->get();
	}

}