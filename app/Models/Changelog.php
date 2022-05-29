<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Changelog extends Model {

	protected $connection = 'mysql2';
	protected $table = 'changelog';
	public $timestamps = false;

	public function getImageData() {
		$image = new SimpleImage;
		$image->load_base64($this->image);
		return $image->output_base64();
	}

	public function getType() {
		return self::getTypes()[$this->type];
	}

	public static function getTypes() {
		return [
			'feature' => 'New feature',
			'bug' => 'Fixed bug',
			'speed-improvement' => 'Speed improvement',
			'visual-update' => 'Visual update'
		];
	}

}