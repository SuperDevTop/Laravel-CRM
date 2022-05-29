<?php

namespace App\Classes;

class CommonFunctions {
	public static function formatDatetime($input) {
		if ($input == 'now') {
			return date('d-m-Y H:i', time());
		} else {
			$timestamp = strtotime($input);
			if ($timestamp < 32140800) return '';

			// If the hours and minutes is 00:00 it probably means we didn't save the time part, so we only show the date
			if (date('H:i', $timestamp) == '00:00')
				return date('d-m-Y', $timestamp);

			// Return the date and time
			return date('d-m-Y H:i', $timestamp);
		}
	}

	public static function formatDate($input) {
		if ($input == 'now') {
			return date('d-m-Y', time());
		} else {
			$timestamp = strtotime($input);
			if ($timestamp  < 32140800) return '';
			return date('d-m-Y', $timestamp);
		}
	}

	public static function dateTimePickerToMysqlDateTime($input) {
		$timestamp = strtotime($input);
		return date('Y-m-d H:i', $timestamp);
	}

	public static function getMysqlDate() {
		return date('Y-m-d H:i', time());
	}

	public static function formatNumber($value) {
		return number_format($value, 2, '.', ',');
	}
	
	public static function parseMaskedDateTime($input) {
		return date('Y-m-d H:i', strtotime($input));
	}
	
	public static function formatMaskedDateTime($input) { 
		if ($input == '1970-01-01 00:00:00' || $input == null) {
			return '';
		} else {
			return date('d-m-Y H:i', strtotime($input));
		}
	}

	public static function getReceiptText($customerId) {
		$text = Settings::setting('receiptText');

		$text = str_replace('{customerName}', Customer::find($customerId)->getCustomerName(), $text);
		$text = str_replace('{currentDate}', date('d-m-Y'), $text);

		return $text;
	}

	// Used for the js files. This changes the filenames as they change so we don't have problems with cache.
	public static function auto_version($file)
	{
	  if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
	    return $file;

	  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
	  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
	}

	// PHP FTW! As strtotime(date +x months) doesn't take in consideration days (31-01-2014 +1 month = 03-03-2014), we have to do the calculation ourselves.
	// 																				So I wrote this beautifully commented algorithm to solve this problem :)
	public static function addMonthsToDate($date, $months) {
		// First, calculate the month
		$month = date('m', strtotime($date));
		// Now, calculate the next renewal date
		$newDate = date('d-m-Y', strtotime($date . ' +' . $months . ' months'));
		// Now calculate the correct month
		$firstOfMonth = '01-' . date('m-Y', strtotime($date));
		$correctMonth = date('m', strtotime($firstOfMonth . ' +' . $months . ' months'));
		// Check if the month is correct. If not, get the last day of the month before
		if ($correctMonth != date('m', strtotime($newDate)))
			$newDate = date('d-m-Y', strtotime('last day of ' . date('d-m-Y', strtotime($newDate . ' -1month'))));

		// Return the date :)
		return $newDate;
	}

	public static function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	// Create the correct array for a ui-select dropdown box
	public static function getDropdownData($type, $metadata) {
		switch($type) {
			case 'employees':
				$employees = User::where('disabled', '=', 0)->orWhere('id', '=', $metadata)->select('id', 'firstname', 'lastname')->get();
				$data = [];
				foreach($employees as $employee) {
					array_push($data, ['id' => $employee->id, 'name' => $employee->full_name]);
				}
				return $data;
			break;
		}
	}

	public static function formatBytes($bytes, $precision = 2) { 
	    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

	    $bytes = max($bytes, 0); 
	    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
	    $pow = min($pow, count($units) - 1); 

	    // Uncomment one of the following alternatives
	    $bytes /= pow(1024, $pow);
	    // $bytes /= (1 << (10 * $pow)); 

	    return round($bytes, $precision) . ' ' . $units[$pow]; 
	}

	public static function getExistingCustomerAdType() {
		$adType = AdType::where('type', '=', 'Existing Customer')->first();
		if ($adType)
			return $adType->id;
		else
			return 0;
	}

	public static function getCompletedJobStatus() {
		$jobStatus = JobStatus::where('type', '=', 'Completed')->first();
		if ($jobStatus)
			return $jobStatus->id;
		else
			return 0;
	}
}