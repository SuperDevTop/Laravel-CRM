<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings;

class MediaItem extends Model {

	protected $table = 'media';
	public $timestamps = false;

	public function toArray() {
		$array = parent::toArray();
		$array['url'] = $this->getUrl();
		$array['thumbUrl'] = $this->getThumbUrl();
		$array['hasThumbnail'] = in_array(strtolower(pathinfo($this->filename, PATHINFO_EXTENSION)), ['jpg', 'png', 'gif', 'bmp']);
		return $array;
	}

	public function getUrl() {
		return '/media/' . pathinfo($this->filename, PATHINFO_FILENAME) . '_' . $this->hash . '.' . pathinfo($this->filename, PATHINFO_EXTENSION);
	}

	public function getExternalUrl() {
		return '/extmedia/' . pathinfo($this->filename, PATHINFO_FILENAME) . '_' . $this->hash . '.' . pathinfo($this->filename, PATHINFO_EXTENSION) . '/1337head';
	}

	public function getThumbUrl() {
		return '/media/' . pathinfo($this->filename, PATHINFO_FILENAME) . '_' . $this->hash . '.' . pathinfo($this->filename, PATHINFO_EXTENSION) . '?thumb=1';
	}

	public function getPath() {
		return storage_path() . '/files/' . Settings::setting('installationId') . '/media/' . pathinfo($this->filename, PATHINFO_FILENAME) . '_' . $this->hash . '.' . pathinfo($this->filename, PATHINFO_EXTENSION);
	}

	public function getThumbnailPath() {
		return storage_path() . '/files/' . Settings::setting('installationId') . '/media/' . pathinfo($this->filename, PATHINFO_FILENAME) . '_' . $this->hash . '_thumb.png';
	}

}