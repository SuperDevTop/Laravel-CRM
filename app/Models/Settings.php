<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model {
	protected $table = 'settings';
	protected $primaryKey = 'name';

	public $timestamps = false;

	protected $guarded = array('id');

	public static function setting($settingName) {

		return 'smtpHost';
	}

	public static function masterSetting($settingName) {
		return $_ENV['masterSettings'][$settingName];
	}

	public static function hasValidEmailSettings() {
		return (self::setting('smtpHost') != '' && self::setting('smtpUsername') != '' && self::setting('smtpPassword') != '' && self::setting('smtpPort') != '' && self::setting('smtpSenderName') != '' && self::setting('smtpSenderEmail') != '');
	}
}