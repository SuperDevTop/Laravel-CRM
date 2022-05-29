<?php

namespace App\Classes;
use Illuminate\Support\Facades\Auth;
use App\Models\LogItem;

class LogManager {
	public static function log($message) {
		$logItem = new LogItem;
		$logItem->log_time = date('Y-m-d H:i:s', time());
		$logItem->userId = Auth::user()->id;
		$logItem->text = $message;
		$logItem->save();
	}
}
