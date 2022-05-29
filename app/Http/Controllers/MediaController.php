<?php

namespace App\Http\Controllers;

use App\Models\MediaItem;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;

class MediaController extends BaseController {

	public function getMedia($filename) {
		// Get the filename path info
		$pathInfo = pathinfo($filename);

		// Get the extension
		$extension = $pathInfo['extension'];

		// Get the base filename (without extension)
		$filename = $pathInfo['filename'];

		// Now, figure out the hash of the file
		$hash = explode('_', $filename);
		$hash = $hash[count($hash) - 1];

		// And get the filename without the hash :)
		$filename = substr($filename, 0, strlen($filename) - 17) . '.' . $extension;

		$mediaItem = MediaItem::where('filename', '=', $filename)->where('hash', '=', $hash)->first();

		if (Request::has('thumb') && Request::get('thumb') == 1)
			$response = Response::make(file_get_contents($mediaItem->getThumbnailPath()));
		else
			$response = Response::make(file_get_contents($mediaItem->getPath()));

		switch($extension) {
			case 'jpg':
				$response->header('content-type', 'image/jpg');
			break;
			case 'png':
				$response->header('content-type', 'image/png');
			break;
			case 'gif':
				$response->header('content-type', 'image/gif');
			break;
		}

		return $response;
	}

}