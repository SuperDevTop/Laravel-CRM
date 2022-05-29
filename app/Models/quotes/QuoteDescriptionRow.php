<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class QuoteDescriptionRow extends Model {

	protected $table = 'quotedescriptionrows';

	public function toArray() {
		$array = parent::toArray();

		$array['image1'] = MediaItem::find($this->image1);
		$array['image2'] = MediaItem::find($this->image2);

		return $array;
	}

}